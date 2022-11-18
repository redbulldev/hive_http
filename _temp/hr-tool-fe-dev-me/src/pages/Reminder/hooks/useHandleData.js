import { useIsAuthenticated, useMsal } from '@azure/msal-react';
import { useState } from 'react';
import { useEffect } from 'react';
import { useCallMsApi } from '../../../components/MsCalendar';
import moment from 'moment';
import 'moment-timezone';
import { eventTag } from '../../../components/MsCalendar/constants';

export default function useHandleData({ params }) {
  const isAuthenticated = useIsAuthenticated();
  const [data, setData] = useState(null);
  const { api: getAll, isTokenReady } = useCallMsApi({
    type: 'getAll',
    params,
  });

  // const test = {
  //   cvId: 15490,
  //   eventId: 111,
  //   position: 'Dev',
  //   level: 'senior',
  //   fullName: 'Le Nhu Ngoc',
  //   priority: 'low',
  //   description:
  //     'Vừa qua giai đoạn tích lũy kinh nghiệm. Dự đoán khả năng đáp ứng công việc tốt.',
  // };

  // console.log(JSON.stringify(test));

  const handleData = data => {
    const result = {};
    data.forEach(event => {
      const day = event?.start?.dateTime;
      const des = event?.body?.content;
      if (day) {
        const value = moment
          .utc(day)
          .tz('Asia/Ho_Chi_Minh')
          .format('DD/MM/YYYY');
        // convert item
        const item = {
          ...event,
          startDate: value,
        };
        // read data from hrtool
        const str = des.slice(
          des.indexOf(eventTag.open) + eventTag.open.length,
          des.indexOf(eventTag.close),
        );

        try {
          const hrtoolData = JSON.parse(str);
          item.hrtoolData = hrtoolData;
        } catch (e) {}

        const thatDayArr = result?.[value];
        if (item?.hrtoolData) {
          if (!thatDayArr) {
            result[value] = [item];
          } else {
            result[value].push(item);
          }
        }
      }
    });
    return result;
  };

  useEffect(async () => {
    if (isAuthenticated && isTokenReady) {
      const response = await getAll();
      const result = handleData(response.data.value);
      setData(result);
    }
  }, [isAuthenticated, isTokenReady, params]);

  return { data };
}
