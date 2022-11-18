import { GeneralSelect } from '..';
import { departmentApi } from '../../../api/departmentAPI';

export default function DepartmentSelect({ ...rest }) {
  return (
    <GeneralSelect
      {...rest}
      valueKey="id"
      contentKey="title"
      api={departmentApi.getAll}
    />
  );
}
