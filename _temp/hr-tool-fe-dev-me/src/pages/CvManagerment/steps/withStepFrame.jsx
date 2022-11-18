import { SaveOutlined } from '@ant-design/icons';
import { Form, message } from 'antd';
import React, { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { useParams } from 'react-router-dom';
import FormInput from '../../../components/Form/FormInput';
import { CV_STATUS_DEFAULT_VALUE, CV_STEP } from '../../../constants';
import StepReason from '../form-components/StepReason';
import StepStatus from '../form-components/StepStatus';
import StepSubmitBtn from '../form-components/StepSubmitBtn';

export default function withStepFrame(WrappedComponent) {
  return function ParentComponent(props) {
    const { t } = useTranslation();
    const [form] = Form.useForm();
    const { id } = useParams();

    const [isFailed, setIsFailed] = useState(false);
    const [submitLoading, setSubmitLoading] = useState(false);
    const [requiredFields, setRequiredFields] = useState([]);
    const [config, setConfig] = useState({});

    useEffect(async () => {
      if (config.api) {
        try {
          const res = await config.api(id);
          const result = res.data.data;
          let filledObject = {};
          if (config.fillData) {
            filledObject = config.fillData(result);
          }
          const final = {
            status: result?.status ?? CV_STATUS_DEFAULT_VALUE.PENDING,
          };
          for (let key in result) {
            if (!filledObject.hasOwnProperty(key)) {
              final[key] = result[key];
            }
          }
          form.setFieldsValue({
            ...filledObject,
            ...final,
            status: result?.status ?? CV_STATUS_DEFAULT_VALUE.PENDING,
          });
          if (result?.status === CV_STATUS_DEFAULT_VALUE.FAILED) {
            setIsFailed(true);
          }
          if (config.fillData) {
            config.fillData(result);
          }
        } catch (e) {
          if (config.catchFetchData) {
            config.catchFetchData(e);
          }
          form.setFieldsValue({
            status: CV_STATUS_DEFAULT_VALUE.PENDING,
          });
          console.log('e :', e);
        }
      }
    }, [config.api]);

    useEffect(() => {
      if (Object.keys(config).length > 0) {
        setRequiredFields(config.requiredFields);
      }
    }, [config]);

    const GeneralInput = ({ disabled = false, required, name, ...rest }) => {
      return (
        <FormInput
          {...rest}
          name={name}
          disabled={disabled || isFailed}
          required={requiredFields.includes(name)}
        />
      );
    };
    const Input = ({ ...rest }) => <GeneralInput {...rest} />;
    const TextArea = ({ ...rest }) => <GeneralInput {...rest} textArea />;
    const Reason = ({ ...rest }) => <StepReason {...rest} form={form} />;
    const Status = ({ ...rest }) => (
      <StepStatus {...rest} setIsFailed={setIsFailed} form={form} />
    );

    const submitWithoutValidation = values => {
      let data = {
        reason: values.reason,
        status: 0,
      };
      if (config.submitFailedObject) {
        data = config.submitFailedObject(values);
      }
      finalFinish(data);
    };

    const onFinish = values => {
      if (config.submitObject) {
        const data = config.submitObject(values);
        finalFinish(data);
      }
    };

    const finalFinish = async data => {
      setSubmitLoading(true);
      try {
        for (let key in data) {
          if (data[key] && typeof data[key] === 'string')
            data[key] = data[key].trim();
        }
        if (config.submitApi) await config.submitApi({ cv_id: id, ...data });
        if (config.afterSubmit) {
          config.afterSubmit(data);
        }
        message.success(t('common.updateSuccessfully'));
        setSubmitLoading(false);
        props.onClose();
        if (props.reload) props.reload();
      } catch (e) {
        setSubmitLoading(false);
        if (!e.status) message.error(t('typework.networkError'));
        else message.error(e?.message);
      }
    };

    const Frame = ({ step = props.currentStep, children, title }) => {
      const getTitle = () => {
        if (title) return title;
        if (!step) return '';
        const found = CV_STEP[step];
        return t(`cv.${found.title}`);
      };
      return (
        <div className="step-frame">
          <Form
            form={form}
            onFinish={onFinish}
            layout="vertical"
            className="standard-form"
          >
            <div className="step-header">
              <h4>{getTitle()}</h4>
              <StepSubmitBtn
                form={form}
                normalBtn
                icon={<SaveOutlined />}
                requiredFields={requiredFields}
                loading={submitLoading}
                onClick={() => {
                  if (form.getFieldValue('status') === 0) {
                    submitWithoutValidation(form.getFieldsValue());
                  } else form.submit();
                }}
                disabled={!props.isDecision}
                {...config.submitProps}
              />
            </div>
            <div className="step-content">{children}</div>
          </Form>
        </div>
      );
    };

    return (
      <WrappedComponent
        {...props}
        Frame={Frame}
        isFailed={isFailed}
        setIsFailed={setIsFailed}
        form={form}
        FormInput={Input}
        FormTextArea={TextArea}
        Reason={Reason}
        Status={Status}
        setRequiredFields={setRequiredFields}
        setConfig={setConfig}
      />
    );
  };
}
