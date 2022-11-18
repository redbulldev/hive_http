export function removeWhiteSpaces(obj) {
  const newObj = Object.keys(obj).reduce((acc, cur) => {
    if (obj[cur] && typeof obj[cur] === 'string') {
      acc[cur] = obj[cur].trim();
    } else {
      acc[cur] = obj[cur];
    }
    return acc;
  }, {});

  return newObj;
}
