import { HistoryOutlined, CalendarOutlined } from '@ant-design/icons';
import DashBoardIcon from '../components/Sidebar/components/DashBoardIcon.jsx';
import ManagementIcon from '../components/Sidebar/components/ManagementIcon.jsx';
import PlanIcon from '../components/Sidebar/components/PlanIcon.jsx';
import RequireIcon from '../components/Sidebar/components/RequireIcon.jsx';
import SettingIcon from '../components/Sidebar/components/SettingIcon.jsx';

export const LIST_ROUTES = [
  {
    path: '/statistic',
    role: 'dashboard',
    title: 'statistic',
    icon: <DashBoardIcon />,
    child: [
      {
        path: '/statistic/cv',
        title: 'cv',
        role: 'dashboard',
        icon: '',
        child: [],
      },
      {
        path: '/statistic/request',
        title: 'request',
        role: 'dashboard',
        icon: '',
        child: [],
      },
    ],
  },
  {
    path: '/request',
    role: 'request',
    title: 'request',
    icon: <RequireIcon />,
    child: [],
  },
  {
    path: '/plan',
    role: 'plan',
    subPath: '/plan/detail',
    title: 'plan',
    icon: <PlanIcon />,
    child: [],
  },
  {
    path: '/cv',
    role: 'cv',
    title: 'cv-managerment',
    icon: <ManagementIcon />,
    child: [],
  },
  {
    path: '/email-history',
    title: 'emailHistory',
    role: 'email_history',
    icon: <HistoryOutlined />,
    child: [],
  },
  {
    path: '/reminder',
    title: 'reminder',
    icon: <CalendarOutlined />,
    child: [],
  },
  {
    path: '/setting',
    title: 'setting',
    role: [
      'general',
      'positions',
      'level',
      'language',
      'source',
      'department',
      'role',
      'users',
      'type_work',
      'email',
    ],
    icon: <SettingIcon />,
    child: [
      {
        path: '/setting/common',
        title: 'common',
        role: 'general',
        icon: '',
        child: [],
      },
      {
        path: '/setting/user',
        title: 'user',
        role: 'users',
        icon: '',
        child: [],
      },
      {
        path: '/setting/role',
        title: 'role',
        role: 'role',
        icon: '',
        child: [],
      },
      {
        path: '/setting/position',
        title: 'position',
        role: 'positions',
        icon: '',
        child: [],
      },
      {
        path: '/setting/department',
        title: 'department',
        role: 'department',
        icon: '',
        child: [],
      },
      {
        path: '/setting/level',
        title: 'level',
        role: 'level',
        icon: '',
        child: [],
      },
      {
        path: '/setting/type-work',
        title: 'type-work',
        role: 'type_work',
        icon: '',
        child: [],
      },
      {
        path: '/setting/language',
        title: 'language',
        role: 'language',
        icon: '',
        child: [],
      },
      {
        path: '/setting/source',
        title: 'source',
        role: 'source',
        icon: '',
        child: [],
      },
      {
        path: '/setting/email',
        title: 'email',
        role: 'email',
        icon: '',
        child: [],
      },
      {
        path: '/setting/release',
        title: 'release',
        icon: '',
        child: [],
      },
    ],
  },
];
