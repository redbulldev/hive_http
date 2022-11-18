import React from 'react';
import { GeneralSelect } from '../';
import levelApi from '../../../api/levelApi';

export default function LevelSelect({ ...rest }) {
  return (
    <GeneralSelect
      {...rest}
      valueKey="id"
      contentKey="title"
      api={levelApi.getLevel}
    />
  );
}
