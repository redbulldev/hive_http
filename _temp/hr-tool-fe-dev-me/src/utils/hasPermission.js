export const hasPermission = (userInfo, role, roleItem) => {
  const { permission } = userInfo;
  if (!permission || !permission[role]) return false;
  return permission[role][roleItem];
};
