import React from 'react';
import { DEFAULT_PRIORITY } from '../../../constants/requestPage';
import GeneralSelect from './GeneralSelect';

function PrioritySelect({ ...props }) {
  return (
    <GeneralSelect
      {...props}
      valueKey="value"
      contentKey="title"
      fetchedItems={DEFAULT_PRIORITY}
    />
  );
}

export default PrioritySelect;
