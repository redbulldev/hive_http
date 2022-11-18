import React from 'react';
import HrInterview from './HrInterview';
import TechInterview from './TechInterview';
import '../scss/interview.scss';

export default function Interview(props) {
  return (
    <div className="interview">
      <div className="hr-interview">
        <HrInterview {...props} />
      </div>
      <div className="tech-interview">
        <TechInterview {...props} />
      </div>
    </div>
  );
}
