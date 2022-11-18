import { message } from 'antd';
import { t } from 'i18next';
import { useEffect, useState } from 'react';
import commonSettingApi from '../../../api/commonSettingApi';
import languageApi from '../../../api/languageApi';
import { getLevel } from '../../../api/level/levelApi';
import { positionApi } from '../../../api/positionApi';
import typeworkApi from '../../../api/typeworkApi';
import { PARAMS_GET_ALL } from '../../../constants/requestPage';

function useFetchDefaultField() {
  const [listLevel, setListLevel] = useState([]);
  const [listTypeWork, setListTypeWork] = useState([]);
  const [listLanguages, setListLanguages] = useState([]);
  const [listPosition, setListPosition] = useState([]);
  const [listDeadlineDay, setListDeadlineDay] = useState([]);
  const fetchListLevel = async () => {
    try {
      // call api get list level with status = 1
      const response = await getLevel(PARAMS_GET_ALL);
      setListLevel(response.data.data);
    } catch (error) {
      if (!error.status) {
        message.error(t('request.lostConnect'));
      } else {
        message.error(t('request.failToFetchListLevel'));
      }
    }
  };

  const fetchListTypeWork = async () => {
    try {
      // call api get list level with status = 1
      const response = await typeworkApi.getAll(PARAMS_GET_ALL);
      setListTypeWork(response.data.data);
    } catch (error) {
      if (!error.status) {
        message.error(t('request.lostConnect'));
      } else {
        message.error(t('request.failToFetchListTypeWork'));
      }
    }
  };

  const fetchListPosition = async () => {
    try {
      const response = await positionApi.getAll(PARAMS_GET_ALL);
      setListPosition(response.data.data);
    } catch (error) {
      if (!error.status) {
        message.error(t('request.lostConnect'));
      } else {
        message.error(t('request.failToFetchListTypeWork'));
      }
    }
  };

  const fetchListLanguages = async () => {
    try {
      // call api get list level with status = 1
      const response = await languageApi.getAll(PARAMS_GET_ALL);
      setListLanguages(response.data.data);
    } catch (error) {
      if (!error.status) {
        message.error(t('request.lostConnect'));
      } else {
        message.error(t('request.failToFetchListTypeWork'));
      }
    }
  };
  const fetchDeadlineDay = async () => {
    try {
      const response = await commonSettingApi.getById(1); // 1 is default id of common setting
      setListDeadlineDay(response.data.data);
    } catch (error) {
      if (!error.status) {
        message.error(t('request.lostConnect'));
      } else {
        message.error(t('request.failToFetchListTypeWork'));
      }
    }
  };

  useEffect(() => {
    fetchListLevel();
    fetchListTypeWork();
    fetchListPosition();
    fetchListLanguages();
    fetchDeadlineDay();
  }, []);
  return {
    listDeadlineDay,
    listLanguages,
    listLevel,
    listPosition,
    listTypeWork,
  };
}

export default useFetchDefaultField;
