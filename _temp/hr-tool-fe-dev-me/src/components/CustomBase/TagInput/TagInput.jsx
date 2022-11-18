import { CloseCircleFilled, CloseCircleOutlined } from '@ant-design/icons';
import { Input, message, Tag, Tooltip } from 'antd';
import React, { useEffect, useRef, useState } from 'react';
import { useTranslation } from 'react-i18next';
import './tagInput.scss';

export default function TagInput({
  onChange: setTags,
  value: tags = [],
  placeholder,
  existMessage,
  ...inputProps
}) {
  const { disabled } = inputProps;
  const { t } = useTranslation();

  const [inputValue, setInputValue] = useState('');
  const [trigger, setTrigger] = useState(undefined);

  const containerRef = useRef();

  const onChangeInput = e => {
    const value = e.target.value;
    setInputValue(value);
  };

  const addTag = value => {
    setInputValue('');
    value = value?.trim();
    if (value) {
      if (tags.includes(value)) {
        return message.error(existMessage || t('component.existTag'));
      }
      const arr = [...tags, value];
      setTags(arr);
    }
  };

  const onPressEnter = e => {
    const value = e.target.value;
    addTag(value);
  };

  const onKeyDown = e => {
    if (e.code === 'Space') {
      const value = e.target.value;
      addTag(value);
    }
  };

  const closeTag = tag => {
    setTags(tags.filter(item => item !== tag));
  };

  const getTags = () => {
    const renderTags = tags => {
      return tags.map(tag => (
        <Tag closable={!disabled} onClose={() => closeTag(tag)} key={tag}>
          {tag}
        </Tag>
      ));
    };

    const width = containerRef.current?.offsetWidth - 60;
    let sum = 0;
    const dotIndex = tags.findIndex(tag => {
      sum += tag.length * 6 + 40;
      if (sum > width) return true;
      return false;
    });

    if (dotIndex !== -1) {
      const tooltipContent = tags.slice(dotIndex).join(', ');
      return (
        <>
          {renderTags(tags.slice(0, dotIndex))}
          <Tooltip
            placement="topLeft"
            title={tooltipContent}
            arrowPointAtCenter
          >
            <Tag>{`+ ${tags.length - dotIndex} ...`}</Tag>
          </Tooltip>
        </>
      );
    }

    return renderTags(tags);
  };

  useEffect(() => {
    window.onresize = e => {
      setTrigger(window.innerWidth);
    };
    return () => {
      window.onresize = undefined;
    };
  }, []);

  return (
    <div className="tag-input" ref={containerRef}>
      <Input
        {...inputProps}
        value={inputValue}
        placeholder={tags.length === 0 ? placeholder : undefined}
        onChange={onChangeInput}
        onPressEnter={onPressEnter}
        onKeyDown={onKeyDown}
        onBlur={onPressEnter}
        prefix={getTags()}
        suffix={
          tags.length !== 0 && !disabled ? (
            <CloseCircleFilled
              style={{
                cursor: 'pointer',
                color: 'rgba(0, 0, 0, 0.25)',
              }}
              onClick={() => setTags([])}
            />
          ) : undefined
        }
      />
    </div>
  );
}
