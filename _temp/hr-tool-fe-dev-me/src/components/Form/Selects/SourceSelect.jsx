import React from 'react';
import { GeneralSelect } from '../';
import sourceApi from '../../../api/sourceApi';

export default function SourceSelect({ ...rest }) {
  return (
    <GeneralSelect
      {...rest}
      valueKey="id"
      contentKey="title"
      api={sourceApi.getAll}
    />
  );
}
