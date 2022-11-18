import { useCallback, useRef } from 'react';

export const useExportImg = fileName => {
  const ref = useRef(null);
  const onExport = useCallback(() => {
    if (ref.current) {
      const link = document.createElement('a');
      link.download = fileName;
      link.href = ref.current.toBase64Image('image/png', 1);
      link.click();
    }
  }, [fileName]);
  return {
    ref,
    onExport,
  };
};
