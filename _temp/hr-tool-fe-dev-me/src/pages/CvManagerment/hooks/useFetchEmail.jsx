import { useEffect, useState } from 'react';
import { getEmailHistory, getTemplateEmail } from '../../../api/historyCv';

export default function useFetchEmail(cv_id, cv_step, cv_status) {
  const [emailHistoryResponse, setEmailHistoryResponse] = useState({});
  const [totalHistoryEmail, setTotalHistoryEmail] = useState(0);
  const [emailTemplate, setEmailTemplate] = useState({});
  const fetchEmailHistory = async () => {
    await getEmailHistory({
      cv_id,
      cv_step,
      cv_status,
    })
      .then(res => {
        setEmailHistoryResponse(res.data.data?.[0]);
        setTotalHistoryEmail(res.data.total);
      })
      .catch(e => {
        console.log(e);
      });
  };

  const fetchTemplateEmail = async () => {
    await getTemplateEmail({
      cv_step,
      cv_status,
    })
      .then(res => {
        setEmailTemplate(res.data.data[0]);
      })
      .catch(e => console.log(e));
  };

  useEffect(() => {
    fetchTemplateEmail();
    if (emailTemplate) {
      fetchEmailHistory();
    }
  }, [cv_id, cv_step, cv_status]);
  return { emailHistoryResponse, totalHistoryEmail, emailTemplate };
}
