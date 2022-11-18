import { message } from 'antd';
import ImageUploader from 'quill-image-uploader';
import { memo } from 'react';
import { Quill } from 'react-quill';
import uploadApi from '../../../api/uploadApi';
import i18n from '../../../translation/i18n';
import { isLt2M } from '../../../utils/isLt2M';

// Custom Undo button icon component for Quill editor. You can import it directly
// from 'quill/assets/icons/undo.svg' but I found that a number of loaders do not
// handle them correctly
const CustomUndo = () => (
  <svg viewBox="0 0 18 18">
    <polygon className="ql-fill ql-stroke" points="6 10 4 12 2 10 6 10" />
    <path
      className="ql-stroke"
      d="M8.09,13.91A4.6,4.6,0,0,0,9,14,5,5,0,1,0,4,9"
    />
  </svg>
);
function undoChange() {
  this.quill.history.undo();
}
function redoChange() {
  this.quill.history.redo();
}
export const modules = {
  toolbar: {
    container: '#toolbar',
    handlers: {
      undo: undoChange,
      redo: redoChange,
    },
  },
  history: {
    delay: 500,
    maxStack: 100,
    userOnly: true,
  },
  imageUploader: {
    upload: file => {
      if (!isLt2M(file)) {
        message.error(i18n.t('cv.limited_file'));
        return;
      }
      return new Promise((resolve, reject) => {
        uploadApi
          .post(file)
          .then(response => {
            resolve(response.data.data);
          })
          .catch(err => {
            reject(err);
            message.error(err.data.message);
          });
      });
    },
  },
};
// Redo button icon component for Quill editor
const CustomRedo = () => (
  <svg viewBox="0 0 18 18">
    <polygon className="ql-fill ql-stroke" points="12 10 14 12 16 10 12 10" />
    <path
      className="ql-stroke"
      d="M9.91,13.91A4.6,4.6,0,0,1,9,14a5,5,0,1,1,5-5"
    />
  </svg>
);

// Undo and redo functions for Custom Toolbar

// Add sizes to whitelist and register them
const Size = Quill.import('attributors/style/size');
Size.whitelist = ['24px', '48px', '100px', '200px'];
Quill.register(Size, true);
Quill.register('modules/imageUploader', ImageUploader);

// Add fonts to whitelist and register them
const Font = Quill.import('formats/font');
Font.whitelist = [
  'arial',
  'comic-sans',
  'courier-new',
  'georgia',
  'helvetica',
  'lucida',
  'times-new-roman',
];
Quill.register(Font, true);
// Modules object for setting up the Quill editor

// Formats objects for setting up the Quill editor
export const formats = [
  'header',
  'font',
  'size',
  'bold',
  'italic',
  'underline',
  'align',
  'strike',
  'script',
  'blockquote',
  'background',
  'list',
  'bullet',
  'indent',
  'link',
  'image',
  'color',
  'code-block',
];

// Quill Toolbar component
const QuillToolbar = () => (
  <div id="toolbar">
    <span className="ql-formats">
      <select className="ql-font" defaultValue="arial">
        <option value="arial">Arial</option>
        <option value="comic-sans">Comic Sans</option>
        <option value="courier-new">Courier New</option>
        <option value="georgia">Georgia</option>
        <option value="helvetica">Helvetica</option>
        <option value="lucida">Lucida</option>
        <option value="times-new-roman">Times</option>
      </select>
      <select className="ql-size">
        <option selected>Default</option>
        <option value="24px">Small</option>
        <option value="48px">Medium</option>
        <option value="100px">Large</option>
        <option value="200px">Huge</option>
      </select>
      <select className="ql-header" defaultValue="3">
        <option value="1">Heading</option>
        <option value="2">Subheading</option>
        <option value="3">Normal</option>
      </select>
    </span>
    <span className="ql-formats">
      <button className="ql-bold" />
      <button className="ql-italic" />
      <button className="ql-underline" />
      <button className="ql-strike" />
    </span>
    <span className="ql-formats">
      <button className="ql-list" value="ordered" />
      <button className="ql-list" value="bullet" />
      <button className="ql-indent" value="-1" />
      <button className="ql-indent" value="+1" />
    </span>
    <span className="ql-formats">
      <button className="ql-blockquote" />
    </span>
    <span className="ql-formats">
      <select className="ql-align" />
      <select className="ql-color" />
      <select className="ql-background" />
    </span>
    <span className="ql-formats">
      <button className="ql-code-block" />
    </span>
    <span className="ql-formats">
      <button className="ql-undo">
        <CustomUndo />
      </button>
      <button className="ql-redo">
        <CustomRedo />
      </button>
      <button className="ql-image">
        <svg viewBox="0 0 18 18">
          {' '}
          <rect
            className="ql-stroke"
            height="10"
            width="12"
            x="3"
            y="4"
          ></rect>{' '}
          <circle className="ql-fill" cx="6" cy="7" r="1"></circle>{' '}
          <polyline
            className="ql-even ql-fill"
            points="5 12 5 11 7 9 8 10 11 7 13 9 13 12 5 12"
          ></polyline>{' '}
        </svg>
      </button>
    </span>
  </div>
);

export default memo(QuillToolbar);
