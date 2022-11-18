import { GeneralSelect } from '..';
import languageApi from '../../../api/languageApi';

export default function LanguageSelect({ ...rest }) {
  return (
    <GeneralSelect
      {...rest}
      valueKey="title"
      contentKey="title"
      api={languageApi.getAll}
    />
  );
}
