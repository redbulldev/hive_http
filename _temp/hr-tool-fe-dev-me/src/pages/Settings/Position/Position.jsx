import { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { useSelector } from 'react-redux';
import { positionApi } from '../../../api/positionApi';
import NoPermission from '../../../components/NoPermission';
import { Header, TableMain, useTable } from '../../../components/Table';
import { createPositionColumns } from '../../../constants/position';
import { hasPermission } from '../../../utils/hasPermission';
import PositionFilter from './components/Filter';
import ModalForm from './components/ModalForm';
function Position() {
  const { t } = useTranslation();

  const { userInfor } = useSelector(state => state.auth);
  const [formBtnTitle, setFormBtnTitle] = useState('');
  const [formTilte, setFormTitle] = useState('');
  const [isEdit, setIsEdit] = useState(false);
  const { mode, modeText, initial } = useSelector(state => state.drawer);

  const { items, filter, setFilter, loadingTable, fetchData, totalRecord } =
    useTable({
      getApi: positionApi.getAll,
    });
  useEffect(() => {
    if (mode === 'edit') {
      setIsEdit(true);
      setFormTitle(modeText.title);
      setFormBtnTitle(modeText.btn);
    }
  }, [mode]);

  return (
    <div className="position">
      {hasPermission(userInfor, 'positions', 'view') ? (
        <>
          <Header
            title={t('position.position')}
            addPermission={hasPermission(userInfor, 'positions', 'add')}
          />
          <PositionFilter listParams={filter} setFilter={setFilter} />
          <TableMain
            cols={createPositionColumns()}
            callback={createPositionColumns}
            titleLabel={t('position.position')}
            nth
            items={items}
            title={t('position.position')}
            fetchData={fetchData}
            deleteApi={positionApi.delete}
            getApi={positionApi.getAll}
            deleteManyApi={positionApi.multiDelete}
            deletePermission={hasPermission(userInfor, 'positions', 'delete')}
            editPermission={hasPermission(userInfor, 'positions', 'edit')}
            filter={filter}
            Filter={PositionFilter}
            setFilter={setFilter}
            totalRecord={totalRecord}
            loadingTable={loadingTable}
            excelName="position-templates"
          />
          <ModalForm
            addApi={positionApi.postPosition}
            editApi={positionApi.edit}
            fetchData={fetchData}
            setFilter={setFilter}
          />
        </>
      ) : (
        <NoPermission />
      )}
    </div>
  );
}

export default Position;
