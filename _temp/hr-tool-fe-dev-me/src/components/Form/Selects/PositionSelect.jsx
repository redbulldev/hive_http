import React from 'react';
import { GeneralSelect } from '..';
import { positionApi } from '../../../api/positionApi';

export default function PositionSelect({ ...rest }) {
  return (
    <GeneralSelect
      {...rest}
      valueKey="id"
      contentKey="title"
      api={positionApi.getAll}
      dataType="position"
    />
  );
}
