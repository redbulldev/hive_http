import { FormItem } from '../';
import { Editor } from '../../CustomBase';

export default function FormEditor(props) {
  const { disabled, height, hasSuggestions, ...formItemProps } = props;
  const editorProps = { disabled, height, hasSuggestions };

  return (
    <FormItem {...formItemProps} className="form-item-editor">
      <Editor {...editorProps} />
    </FormItem>
  );
}
