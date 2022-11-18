import { Form, Select } from 'antd';
import React, { useEffect, useRef, useState } from 'react';
import { useTranslation } from 'react-i18next';
import ReactQuill from 'react-quill';
import { CUSTOM_FIELDS } from '../../../constants';
import EditorToolbar, { formats, modules } from './EditorToolbar';
import './editor.scss';
import { TAGS_HAVE_INFORMATION } from './constants';

const { Option } = Select;

export default function Editor(props) {
  const { value, onChange, disabled, height, hasSuggestions } = props;
  const [render, setRender] = useState(false);

  const { t } = useTranslation();
  const refQ = useRef();
  let quill = refQ.current?.getEditor();

  useEffect(() => {
    const painText = quill?.getText()?.trim();
    if (
      painText === '' &&
      value &&
      !TAGS_HAVE_INFORMATION.some(tag => value.includes(tag))
    ) {
      onChange('');
    }
  }, [value]);

  const onChangeEditor = e => {
    onChange(e);
  };

  const onChangeSelect = e => {
    const range = quill?.getSelection(true);
    quill?.insertText(range.index, e);
  };

  useEffect(() => {
    const toolbar = document.querySelector('#toolbar');
    const suggest = document.querySelector('.select-suggestion');
    suggest && toolbar?.appendChild(suggest);
    setRender(state => !state);
  }, []);

  return (
    <>
      {hasSuggestions && (
        <Select
          placeholder="Select a suggestion"
          onChange={onChangeSelect}
          className="select-suggestion"
          style={{ width: 150 }}
          value={t('updateCv.option')}
        >
          {CUSTOM_FIELDS.map(field => (
            <Option value={field.value}>{field.key}</Option>
          ))}
        </Select>
      )}

      <EditorToolbar render={render} />
      <ReactQuill
        ref={el => (refQ.current = el)}
        theme="snow"
        value={value || ''}
        onChange={onChangeEditor}
        modules={modules}
        formats={formats}
        style={{ height: height }}
        readOnly={disabled}
      />
    </>
  );
}
