import { useEffect, useState } from 'react';
import requestApi from '../../../api/requestApi';

export default function usePositionLevelAndRequest({ form, cv }) {
  const [requests, setRequests] = useState([]);
  const [auto, setAuto] = useState(null);

  useEffect(async () => {
    try {
      const res = await requestApi.getRequestToAddCv({
        isauto: 1,
      });
      if (res.data?.data?.length > 0) {
        const item = res.data.data[0];
        setAuto([{ ...item, content: item.author_id, fe_type: 'auto' }]);
      } else {
        setAuto([]);
      }
    } catch (e) {
      console.log('e :', e);
    }
  }, []);

  useEffect(() => {
    if (auto?.length > 0) {
      setRequests(pre => [...pre, ...auto]);
    }
  }, [auto]);

  const caseNotFound = isFirstGetFormUpdateCv => {
    setRequests(auto);
    if (!isFirstGetFormUpdateCv) {
      form.setFieldsValue({ request_id: undefined });
    }
  };

  const onChangePositionAndLevel = async (
    e,
    isFirstGetFormUpdateCv = false,
  ) => {
    const selectedPosition = form.getFieldValue('position_id');
    const selectedLevel = form.getFieldValue('level_id');
    if (selectedPosition && selectedLevel) {
      try {
        const res = await requestApi.getRequestToAddCv({
          position_id: selectedPosition,
          level_id: selectedLevel,
        });
        const requestList = res.data.data;
        if (requestList.length > 0) {
          setRequests(pre => {
            return [
              ...pre,
              ...requestList.map(request => {
                return {
                  id: request.id,
                  content: `Requestor: ${request.requestor_id} - Number: ${request.target} - Deadline: ${request.month}/${request.year}`,
                  requestor: request.requestor_id,
                };
              }),
            ];
          });

          if (!isFirstGetFormUpdateCv) {
            form.setFieldsValue({
              request_id: requestList[0].id,
              reviewer_id: requestList[0].requestor_id,
              interviewer_id: requestList[0].requestor_id,
            });
          }
        } else {
          caseNotFound(isFirstGetFormUpdateCv);
        }
      } catch (res) {
        caseNotFound(isFirstGetFormUpdateCv);
        console.log(res);
      }
    }
  };

  const onChangeRequest = e => {
    if (e) {
      const id = requests.find(request => e === request.id)?.requestor;
      form.setFieldsValue({
        reviewer_id: id,
        interviewer_id: id,
      });
    }
  };

  return {
    onChangeRequest,
    onChangePositionAndLevel,
    requests,
    auto,
    setRequests,
  };
}
