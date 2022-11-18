import i18n from '../translation/i18n';

export const validatePhoneNumber = () => {
  const invalidMobile = () =>
    Promise.reject(new Error(i18n.t('validator.invalidMobile')));
  return [
    {
      validator(_, value) {
        if (value) {
          value = value.replace(/ /g, '');
          let len = value?.length;

          // Case "+" sign only at first
          const index = value.indexOf('+');
          if (index > 0) invalidMobile();

          // Case "+" only occur once
          const index2 = value.indexOf('+', index);
          if (index2 !== -1) invalidMobile();

          // Case "84"
          if (value.startsWith('+84')) len = len - 2;
          if (value.startsWith('84')) len = len - 1;

          if (!Number(value) && value !== '0') return invalidMobile();
          if (len > 13)
            return Promise.reject(new Error(i18n.t('updateCv.mobileMax')));
          if (len < 10)
            return Promise.reject(new Error(i18n.t('updateCv.mobileMin')));
        }
        return Promise.resolve();
      },
    },
  ];
};

export const rulesValidateEmail = () => {
  return [
    {
      type: 'email',
      message: i18n.t('updateCv.invalidEmail'),
    },
    {
      validator(_, value) {
        value = value?.trim();
        if (value) {
          if (/.@./g.test(value)) {
            const str = value.split('@')[0];
            if (str.length > 64)
              return Promise.reject(new Error(i18n.t('updateCv.emailTooLong')));
          }
        }
        return Promise.resolve();
      },
    },
  ];
};

const isValidEmail = email => {
  email = email?.trim();
  if (!email) return false;
  if (!email.includes('@')) return false;
  const emailFormat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
  if (!emailFormat.test(email)) return false;
  if (/.@./g.test(email)) {
    const str = email.split('@')[0];
    // Too long
    if (str.length > 64) return false;
  } else return false;

  return true;
};

export const checkEmailsValidator = {
  validator(_, value) {
    if (Array.isArray(value)) {
      const result = value.some(email => !isValidEmail(email));
      if (result)
        return Promise.reject(
          new Error(i18n.t('emailTemplate.existInvalidEmail')),
        );
    }
    return Promise.resolve();
  },
};

export const rulesValidateFullName = () => {
  return [
    {
      whitespace: true,
      required: true,
      message: i18n.t('updateCv.requiredFullName'),
    },
    {
      validator(_, value) {
        value = value.trim();
        if (value && value.length < 3)
          return Promise.reject(new Error(i18n.t('updateCv.fullNameMin')));
        return Promise.resolve();
      },
    },
  ];
};
