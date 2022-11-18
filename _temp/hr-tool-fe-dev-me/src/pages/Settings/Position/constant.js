export const checkOrderbyValue = (listParams, field) => {
  // orderby : 'ascend', 'descend', ''
  let orderBy = '';

  if (listParams?.orderby) {
    const arrFromOrder = listParams.orderby.split('-');
    if (arrFromOrder[0] === field) {
      orderBy = arrFromOrder[1] === 'ASC' ? 'ascend' : 'descend';
    }
  }

  return orderBy;
};
