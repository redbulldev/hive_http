<?php 

/*
* set step
*/
const STEP_REVIEW_HR = 0;

const STEP_REVIEW_PHYSIOGNOMY1 = 1;

const STEP_REVIEW_CV = 2;

// switch review_physiognomy2 and to_interview
const STEP_REVIEW_PHYSIOGNOMY2 = 3;

const STEP_TO_INTERVIEW = 4;

const STEP_INTERVIEW_TECH_HR = [5, 6];	

const STEP_CV_PREOFFER = 6;

const STEP_CV_OFFER = 7;

const STEP_CV_ONBOARD = 8;

const STEP_CV_PROBATION = 9;


/*
* set current
*/
const CURRENT_REVIEW_HR = 1;

const CURRENT_REVIEW_PHYSIOGNOMY1 = 2;

const CURRENT_REVIEW_CV = 3;

// switch review_physiognomy2 and to_interview
const CURRENT_REVIEW_PHYSIOGNOMY2 = 4;	

const CURRENT_TO_INTERVIEW = 5;

const CURRENT_INTERVIEW_TECH_HR = 6;

const CURRENT_CV_PREOFFER = 7;

const CURRENT_CV_OFFER = 8;

const CURRENT_CV_ONBOARD = 9;

const CURRENT_CV_PROBATION = 10;





// const CURRENT_REVIEW_HR = 1;
// const CURRENT_REVIEW_PHYSIOGNOMY1 = 2;
// const CURRENT_REVIEW_CV = 3;
// const CURRENT_REVIEW_PHYSIOGNOMY2 = 4;	
// const CURRENT_TO_INTERVIEW = 5;
// const CURRENT_INTERVIEW_TECH_HR = 6;
// const CURRENT_CV_PREOFFER = 7;
// const CURRENT_CV_OFFER = 8;
// const CURRENT_CV_ONBOARD = 9;
// const CURRENT_CV_PROBATION = 10;

// 'name'=>'review_hr','current'=>1],
// 		['name'=>'review_physiognomy','current'=>2,'where'=>['issecond'=>0]],
// 		['name'=>'review_cv','current'=>3],
// 		['name'=>'cv','current'=>4],
// 		['name'=>['interview_tech','interview_hr'],'current'=>5],
// 		['name'=>'review_physiognomy','current'=>6,'where'=>['issecond'=>1]],
// 		['name'=>'cv_preoffer','current'=>7],
// 		['name'=>'cv_offer','current'=>8],
// 		['name'=>'cv_onboard','current'=>9],
// 		['name'=>'cv_probation','current'=>10],

// 		---------------
//   'review_hr' => ['title' => 'HR Review', 'current' => 1, 'step' => 0, 'old' => 'New'],  //null set 
//     'review_physiognomy1' => ['title' => 'Physiognomy 1', 'current' => 2, 'step' => 1, 'old' => 'HR Review'],
//     'review_cv' => ['title' => 'CV Review', 'current' => 3, 'step' => 2, 'old' => 'Physiognomy 1'],  //null set 
//     'to_interview' => ['title' => 'Pre Interview', 'current' => 4, 'step' => 3, 'old' => 'CV Review'],  //null set 
//     'interview_hr' => ['title' => 'HR Interview', 'current' => 5, 'step' => [4, 5], 'old' => 'Pre Interview'],
//     'interview_tech' => ['title' => 'Tech Interview', 'current' => 5, 'step' => [4, 5], 'old' => 'Pre Interview'],
//     'review_physiognomy2' => ['title' => 'Physiognomy 2', 'current' => 6, 'step' => 5, 'old' => 'HR Tech Interview'],
//     'cv_preoffer' => ['title' => 'Pre Offer', 'current' => 7, 'step' => 6, 'old' => 'Physiognomy1'],  //null set 
//     'cv_offer' => ['title' => 'Offer', 'current' => 8, 'step' => 7, 'old' => 'Pre Offer'],  //null set 
//     'cv_onboard' => ['title' => 'OnBoard', 'current' => 9, 'step' => 8, 'old' => 'Offer'],
//     'cv_probation' => ['title' => 'Probation', 'current' => 10, 'step' => 9, 'old' => 'OnBoard'],  //null set 



// review_physiognomy
// review_physiognomy



// ///////

// review_physiognomy1
// to_interview
// interview_hr
// review_physiognomy2





// ==================
// cv_probation
// cv_onboard
// cv_preoffer
// cv_offer
// interview_tech
// review_cv
// review_hr




