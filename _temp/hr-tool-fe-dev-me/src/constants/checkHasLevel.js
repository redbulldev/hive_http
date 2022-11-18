export const checkHasLevel = (parent, child) => {
  return parent.reduce((acc, cur) => {
    child.forEach(item => {
      if (cur.id === Number(item.id)) {
        acc.push(item.id);
      }
    });
    return acc;
  }, []);
};
