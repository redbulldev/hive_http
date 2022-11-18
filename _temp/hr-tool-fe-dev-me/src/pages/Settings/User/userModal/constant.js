import i18next from 'i18next';
export const FORM_FIELD = [
  {
    name: 'id',
    hidden: true,
  },
  {
    name: 'fullname',
    placehoder: 'nameplace',
    maxlength: 200,
    rule: [
      {
        required: true,
        message: i18next.t('user.validateRequireMessageInput'),
      },
      {
        validator: (_, val) => {
          let message = i18next.t('user.requireFullname');
          let check = false;
          if (val?.trim().length >= 3 && val?.trim().length <= 200) {
            check = true;
            message = '';
          }
          if (!val) {
            check = true;
            message = '';
          }
          return check ? Promise.resolve(message) : Promise.reject(message);
        },
      },
    ],
  },
  {
    name: 'username',
    placehoder: 'usernameplace',
    disabled: true,
    maxlength: 150,
    rule: [
      {
        required: true,
        message: i18next.t('user.validateRequireMessageInput'),
      },
      {
        pattern: new RegExp('^[a-z0-9_-]{3,16}$'),
        message: i18next.t('user.invalid'),
      },
      {
        validator: (_, val) => {
          let message = i18next.t('user.requireUsername');
          let check = false;
          if (!val?.includes(' ')) {
            check = true;
            message = '';
          }
          if (val?.length < 3 || val?.length > 150) {
            check = false;
            message = i18next.t('user.requireLengthUsername');
          }
          if (!val) {
            check = true;
            message = '';
          }
          return check ? Promise.resolve(message) : Promise.reject(message);
        },
      },
    ],
  },
  {
    name: 'email',
    placehoder: 'emailplace',
    rule: [
      {
        required: true,
        message: i18next.t('user.validateRequireMessageInput'),
      },
      {
        type: 'email',
        message: i18next.t('user.validateRequireEmail'),
      },

      {
        validator: (_, val) => {
          let message = i18next.t('user.validateRequireEmailMax');
          let check = false;
          const name = val?.split('@');
          if (name[0].length >= 3 && name[0].length <= 64) {
            check = true;
            message = '';
          }
          if (!val) {
            check = true;
            message = '';
          }
          return check ? Promise.resolve(message) : Promise.reject(message);
        },
      },
    ],
  },
];

export const STATUS_FIELD = ['Khóa', 'Hoạt động'];
