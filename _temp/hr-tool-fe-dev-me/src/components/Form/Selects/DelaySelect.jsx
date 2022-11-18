import { DELAY_OPTIONS } from '../../../constants/cvDetail';
import GeneralSelect from './GeneralSelect';

function DelaySelect({ ...props }) {
  return (
    <GeneralSelect
      {...props}
      valueKey="value"
      contentKey="title"
      fetchedItems={DELAY_OPTIONS}
    />
  );
}

export default DelaySelect;
