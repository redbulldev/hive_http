import { Select } from 'antd';
import { useEffect, useState } from 'react';
import { useSelector } from 'react-redux';
import { FormItem } from '../';
import {
  GET_FULL_LIST_PARAMS,
  RECENTLY_SELECT_LENGTH_MAX,
} from '../../../constants';
import { currentUserSelector } from '../../../global/selectors';
import { RECENTLY_POSITION_NAME } from './constants/generalSelect';
const { Option, OptGroup } = Select;

export default function GeneralSelect(props) {
  const {
    name,
    label,
    required,
    valueKey,
    contentKey,
    api,
    mode,
    storageKey,
    itemsFirst = [],
    index,
    fetchedItems,
    onChange,
    selectClassName,
    renderContent,
    dataType,
    size,
    selectAll,
    form,
    disabled = false,
  } = props;
  const formItemProps = { name, label, required };

  const lowerLabel = label.toLowerCase();
  const placeholder = `Chọn ${lowerLabel}`;
  const selectProps = {
    mode,
    placeholder,
    disabled,
    size,
    className: selectClassName,
    maxTagCount: 'responsive',
    getPopupContainer: trigger => trigger.parentNode,
  };

  const [items, setItems] = useState([]);

  const makeSureUniqueElementArray = arr => {
    return Array.from(new Set(arr));
  };

  const getStorage = () => JSON.parse(localStorage.getItem(storageKey));
  const saveDataToStorage = e => {
    if (Array.isArray(e)) {
      if (e.length <= RECENTLY_SELECT_LENGTH_MAX && e.length > 0) {
        let storage = getStorage();
        if (!storage) storage = e;
        e.forEach((_, i) => {
          storage[i] = e[e.length - i - 1];
        });

        // Delete repeated elements
        const newArr = [];
        storage.forEach(item => {
          if (!newArr.includes(item)) newArr.push(item);
        });
        localStorage.setItem(
          storageKey,
          JSON.stringify(makeSureUniqueElementArray(newArr)),
        );
      }
    }
  };

  const onChangeSelect = e => {
    if (storageKey) {
      saveDataToStorage(e);
    }
    if (onChange) {
      onChange(e);
    }
  };

  const loadDataFromStorage = data => {
    let storage = getStorage();
    if (!storage) return data;
    else {
      if (Array.isArray(storage)) storage = makeSureUniqueElementArray(storage);
      const newData = [];
      storage.forEach(value => {
        newData.push(data.find(item => item[valueKey] === value));
      });
      data.forEach(item => {
        if (!storage.includes(item[valueKey])) newData.push(item);
      });
      return newData;
    }
  };

  const handleDataForPosition = data => {
    const getRecentlyPositions = data => {
      const changeTitle = obj => {
        return { ...obj, parent_title: RECENTLY_POSITION_NAME };
      };
      let storage = getStorage();
      if (storage && Array.isArray(storage)) {
        storage = makeSureUniqueElementArray(storage);
        const recentlyItems = [];
        storage.forEach(value => {
          recentlyItems.push(
            changeTitle(data.find(item => item[valueKey] === value)),
          );
        });

        return { [RECENTLY_POSITION_NAME]: recentlyItems };
      }
      return null;
    };
    const recentlyItems = getRecentlyPositions(data);
    const newPositions = {};
    data.forEach(position => {
      const departmentName = position.parent_title || 'Không rõ';
      const pre = newPositions[departmentName] || [];
      newPositions[departmentName] = [...pre, position];
    });

    return { ...recentlyItems, ...newPositions };
  };

  useEffect(() => {
    async function fetchData() {
      try {
        let data;
        if (fetchedItems) data = fetchedItems;
        else {
          const response = await api(GET_FULL_LIST_PARAMS);
          data = response.data.data;
        }
        data =
          dataType === 'position'
            ? handleDataForPosition(data)
            : loadDataFromStorage(data);
        if (itemsFirst.length > 0) {
          const head = data.filter(item =>
            itemsFirst.includes(item?.[valueKey]),
          );
          const tail = data.filter(
            item => !itemsFirst.includes(item?.[valueKey]),
          );
          data = [...head, ...tail];
        }
        setItems(data);
      } catch (e) {
        console.log('e :', e);
      }
    }
    fetchData();
  }, [fetchedItems]);

  const selectFilter = (input, option) => {
    input = input.trim().toLowerCase();
    return option.children?.toLowerCase()?.includes(input);
  };
  const renderForPositions = items => {
    return Object.keys(items).map((key, id) => {
      return (
        <OptGroup label={key} value={key} key={Math.random()}>
          {items[key].map(value => (
            <Option key={Math.random()} value={value[valueKey]}>
              {value[contentKey]}
            </Option>
          ))}
        </OptGroup>
      );
    });
  };

  const handleSelectAll = () => {
    const list = dataType === 'position' ? getPositionsArray() : items;
    if (!isSelectAll()) {
      form.setFieldsValue({
        [name]: list.map(item => item[valueKey]),
      });
    } else {
      form.setFieldsValue({
        [name]: [],
      });
    }
  };

  // const forceSelectAll = () => {
  //   const list = dataType === 'position' ? getPositionsArray() : items;
  //   form.setFieldsValue({
  //     [name]: list.map(item => item[valueKey]),
  //   });
  // };

  const isSelectAll = () => {
    const values = form.getFieldValue(name);
    const list = dataType === 'position' ? getPositionsArray() : items;
    return !list.some(item => {
      if (!values) return false;
      if (Array.isArray(values) && !values?.includes(item[valueKey]))
        return true;
    });
  };

  const getPositionsArray = () => {
    let arr = [];
    if (dataType === 'position') {
      for (let key in items) {
        if (key !== RECENTLY_POSITION_NAME) {
          arr = [...arr, ...items[key]];
        }
      }
      return arr;
    }
    return null;
  };

  return (
    <FormItem {...formItemProps} type="select">
      <Select
        showSearch
        allowClear
        onChange={onChangeSelect}
        showArrow
        filterOption={selectFilter}
        dropdownRender={origin => {
          let isAll = null;
          const condition = selectAll && form;
          if (condition) {
            isAll = isSelectAll();
          }
          return (
            <>
              {condition && (
                <span
                  style={{
                    display: 'inline-block',
                    width: '100%',
                    minHeight: 32,
                    padding: '5px 12px',
                    color: 'rgba(0, 0, 0, 0.85)',
                  }}
                  className={`ant-select-item ant-select-item-option ${
                    isAll ? 'focus-btn' : 'non-focus-btn'
                  } ${
                    dataType === 'position'
                      ? 'ant-select-item-option-grouped'
                      : ''
                  }`}
                  onClick={handleSelectAll}
                >
                  Chọn tất cả
                </span>
              )}
              {origin}
            </>
          );
        }}
        {...selectProps}
      >
        {dataType === 'position'
          ? renderForPositions(items)
          : items.map((item, id) => {
              return item ? (
                <Option key={id} value={item[valueKey]}>
                  {renderContent
                    ? renderContent(item)
                    : `${
                        index ? `${id}. ${item[contentKey]}` : item[contentKey]
                      }`}
                </Option>
              ) : null;
            })}
      </Select>
    </FormItem>
  );
}
