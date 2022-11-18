export const autoFillCustomField = (cv, content) => {
  let newContent = content;
  cv['level_title'] = cv?.level?.title;
  cv['position_title'] = cv?.position?.title;
  Object.keys(cv).forEach(key => {
    const customFields = '&lt;' + key + '&gt;';
    if (content.includes(customFields)) {
      newContent = newContent.replace(
        customFields,
        cv[key] !== null ? cv[key] : '',
      );
    }
  });
  return newContent;
};
