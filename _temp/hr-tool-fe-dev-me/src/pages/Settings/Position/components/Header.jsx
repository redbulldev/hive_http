import { PlusCircleFilled } from '@ant-design/icons';
import { Button, Col, Row } from 'antd';
import React from 'react';
import { useTranslation } from 'react-i18next';
import { useDispatch } from 'react-redux';
import {
  setIsOpenedDrawer,
  setModeTextDrawer,
} from '../../../../components/Drawer/slice/drawer';
import { hasPermission } from '../../../../utils/hasPermission';
import { getDetailPosition, showFormPosition } from '../reducer';
function PositionHeader(props) {
  const { setFormTitle, setFormBtnTitle, setIsEdit, userInfor } = props;
  const { t } = useTranslation();
  const dispatch = useDispatch();
  const showPosition = async () => {
    setFormBtnTitle(t('position.create'));
    setFormTitle(t('position.createPosition'));
    setIsEdit(false);
    dispatch(setIsOpenedDrawer(true));
    dispatch(getDetailPosition({}));
    dispatch(setModeTextDrawer({ btn: t('position.createPosition') }));
  };

  return (
    <div className="position__create">
      <Row gutter={16}>
        <Col className="gutter-row" span={12}>
          <h2 style={{ marginBottom: 0 }}>{t('position.position')}</h2>
        </Col>
        <Col className="gutter-row position__btn" span={12}>
          {hasPermission(userInfor, 'positions', 'add') ? (
            <>
              <Button
                className="position__btn-create"
                icon={<PlusCircleFilled />}
                color="white"
                type="primary"
                onClick={showPosition}
              >
                {t('position.createPosition')}
              </Button>
            </>
          ) : (
            ''
          )}
        </Col>
      </Row>
    </div>
  );
}

export default React.memo(PositionHeader);
