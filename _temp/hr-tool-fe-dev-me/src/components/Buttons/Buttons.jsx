import React from 'react';
import { Button as AntdButton } from 'antd';
import './styles.scss';
import {
  CloseOutlined,
  EditOutlined,
  PlusCircleFilled,
  SaveOutlined,
} from '@ant-design/icons';

const colorsClassName = {
  white: 'custom-button--white',
  danger: 'custom-button--danger',
  blue: 'custom-button--blue',
};

const definedIcons = {
  add: <PlusCircleFilled />,
  edit: <EditOutlined />,
  save: <SaveOutlined />,
  cancel: <CloseOutlined />,
};

export function Button({ color, icon, children, className, ...rest }) {
  const colorClass =
    color && colorsClassName?.[color] ? colorsClassName?.[color] : '';

  if (icon && typeof icon === 'string' && definedIcons[icon]) {
    icon = definedIcons[icon];
  }

  return (
    <AntdButton
      {...rest}
      className={`custom-button ${colorClass} ${className}`}
      icon={icon}
    >
      {children}
    </AntdButton>
  );
}

const AddBlueButton = props => <Button {...props} type="primary" icon="add" />;

const EditBlueButton = props => (
  <Button {...props} type="primary" icon="edit" />
);

const SaveBlueButton = props => (
  <Button {...props} type="primary" icon="save" />
);

const CancelWhiteButton = props => (
  <Button {...props} type="ghost" icon="cancel" />
);

const NoBorderButton = props => <Button {...props} type="text" />;

export {
  AddBlueButton,
  EditBlueButton,
  SaveBlueButton,
  CancelWhiteButton,
  NoBorderButton,
};
