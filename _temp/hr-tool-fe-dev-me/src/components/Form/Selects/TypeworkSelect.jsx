import { GeneralSelect } from '..';
import typeworkApi from '../../../api/typeworkApi';

export default function TypeworkSelect({ ...rest }) {
  return (
    <GeneralSelect
      {...rest}
      valueKey="id"
      contentKey="title"
      api={typeworkApi.getAll}
    />
  );
}
