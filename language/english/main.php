<?php
//  ------------------------------------------------------------------------ //
//             --  XoopsHP Module --       Xoops e-Learning System           //
//                     Copyright (c) 2005 SUDOW-SOKEN                        //
//                      <http://www.mailpark.co.jp/>                         //
//  ------------------------------------------------------------------------ //
//               Based on XoopsHP1.01 by Yoshi, aka HowardGee.               //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
define("_AM_SECCONF", "Course/Quiz Configuration");
define("_MD_THEFOLLOWING", "The following are quizzes assigned in this course.");
define("_MD_RETURN2INDEX", "Top");
define("_MD_RETURN2QUIZ", "Quiz Index");
define("_MD_RESULTLIST", "Result List");
define("_MD_RESULT_SIMPLE", "Simple List");
define("_MD_RESULT_DETAIL", "Detailed List");
define("_MD_NEXTPAGE", "Next Page");
define("_MD_PREVPAGE", "Previous Page");
define("_MD_CURACTIVESEC", "Current Active Courses");
define("_MD_CLICK2EDIT", "Click to Edit");
define("_MD_ADDARTICLE", "Add Quiz");
define("_MD_TITLEC", "Title:");
define("_MD_CONTENTC", "Quiz File:");
define("_MD_FILE_MAX", "Max size (byte):");
define("_MD_DOADDARTICLE", "Add Quiz!");
define("_MD_LAST20ART", "List Quizzes by Course");
define("_MD_EDIT", "Edit");
define("_MD_EDITARTICLE", "Edit Quiz");
define("_MD_EDITARTID", "Edit Quiz ID:");
define("_MD_FILE2REPLACE", "File to replace the content:");
define("_MD_GO", "Go!");
define("_MD_ADDNEWSEC", "Add a New Course");
// kazuo sudow
// define("_MD_SECNAMEC","Course:");
define("_MD_SECNAMEC", "Course:(Sort)");
define("_MD_MAXCHAR", "(40 characters Max.)");
define("_MD_SECDESC", "Description:");
define("_MD_EXDESC", "(Brief course description.)");
define("_MD_SECQNUM", "Total:");
define("_MD_SECDNUM", "Done:");
define("_MD_GOADDSECTION", "Add Course!");
define("_MD_SAVECHANGES", "Save Changes");
define("_MD_DELETE", "Delete");
define("_MD_READONLY", "<font color='red'>Read Only</font>");
define("_MD_EDITTHISSEC", "Edit Course: %s"); // %s is a section name
define("_MD_THISSECHAS", "This Course has %s quizzes assigned");
define("_MD_RUSUREDELSEC", "Are you sure you want to delete course?");
define("_MD_THISDELETESALL", "This will delete ALL its quizzes!");
define("_MD_YES", "Yes");
define("_MD_NO", "No");
define("_MD_ALL", "All");
define("_MD_DELETETHISART", "Delete Quiz: %s"); // %s is a section name
define("_MD_RUSUREDELART", "Are you sure you want to delete this quiz and all its scores?");
define("_MD_RUSUREDELREC", "Are you sure you want to delete this record?");
define("_MD_DBUPDATED", "Database Updated Successfully!");
define("_MD_ERRORSECNAME", "You must select a course name!");
define("_MD_ERRORARTNAME", "You must enter a quiz title!");
define("_MD_ERRORARTCONT", "The content of the quiz is empty!");
define("_MD_DBNOTUPDATED", "Error adding quiz to the database.<br/>Did you select a course? Please go back and correct the form.");
define("_MD_ERRORQUIZFILE", "Error adding quiz to the database.<br/>The quiz file seems to be mal-formatted.  Try exporting the quiz with the CGI option checked.");
define("_MD_ALERTGUEST", "You are not logged in.  Scores of guest users are not recorded nor sent by email.");

define("_MD_LT_ID", "ID");
define("_MD_LT_TITLE", "Title:(Sort)");
// kazuo sudow append
define("_MD_LT_TITLE2", "Title");
define("_MD_LT_COURSE", "Course");
define("_MD_LT_OWNER", "Owner");
define("_MD_LT_POSTED", "Posted");
define("_MD_LT_ACTION", "Action");
define("_MD_LT_DISPLAY", "Display");
define("_MD_LT_DISPLAY_ON", "Show");
define("_MD_LT_DISPLAY_OFF", "Hide");
define("_MD_LT_HIDDEN", "Hidden");
define("_MD_LT_EXPIRE", "Expiration");
define("_MD_LT_EXPIRED", "Expired");
define("_MD_LT_SET_EXPIRE", "Set expiration");
define("_MD_LT_CURRENT_TIME", "Current Time");
define("_MD_LT_RESULTS", "Results");
define("_MD_LT_DETAILS", "Details");
define("_MD_LT_SCORE", "Score(%)");
define("_MD_LT_STUDENT", "Student");
// kazuo sudow append
define("_MD_LT_GUEST", "Guest");
define("_MD_LT_DATE", "Date & Time");
define("_MD_LT_MYMAX", "My Max");
define("_MD_LT_SITEMAX", "Site Max");
define("_MD_LT_SITEAVG", "Site Avg");
define("_MD_LT_LOW", "Lowest");
define("_MD_LT_AVRG", "Average");
define("_MD_LT_PORTFOLIO", "Portfolio");

define("_XD_FB_USERNAME", "Real name");
define("_XD_FB_ID", "Name typed");
define("_XD_FB_UNAME", "Xoops ID");
define("_XD_FB_QTITLE", "Quiz title");
define("_XD_FB_SCORE", "Score");
define("_XD_FB_START", "Started");
define("_XD_FB_END", "Finished");
define("_XD_FB_FINISHED_BY", "Finished by");
define("_XD_FB_TIMESTAMP", "Time Stamp");
define("_XD_FB_HOST", "Host");
define("_XD_FB_IP", "IP Address");
define("_XD_FB_CMT", "Comment");
define("_XD_FB_OK", "Your result has been sent to you and your instructor by email.");
define("_XD_FB_GUEST", "<font color='red'>Your result has not been recorded nor sent because you are a guest.</font>");
define("_XD_FB_NG", "<font color='red'>Error: Your result has not been sent for some reason.  Maybe try again?</font>");
define("_XD_FB_CLSBTN", "Close");

define("_AM_MSG_ACCESS_ERROR", "Permission error!");
define("_AM_MSG_UPDATE_FAILED", "Update Failed!");
define("_AM_MSG_UPDATE_SUCCEEDED", "Update has been successful.");

//*****************************//
//*     DO NOT EDIT BELOW     *//
//*****************************//
// Name of credit site link
define("_MD_CREDITSITE", "http://sourceforge.jp/projects/xoopshp/");

//*****************************//
//*     DO NOT EDIT BELOW     *//
//*****************************//
define("_XD_FB_CODE4RESULTS_MARKER", "CODE FOR HANDLING SENDING OF RESULTS");
define("_XD_FB_CODE4RESULTS_INSERT", "//-->");
define("_XD_FB_CODE4RESULTS", "
//CODE FOR HANDLING SENDING OF RESULTS

var UserName = '';
var StartTime = (new Date()).toLocaleString();

var ResultForm = '<html><body><form name=\"Results\" action=\"http://www.u-gakugei.ac.jp/~awaji/formmail.cgi\" method=\"post\" enctype=\"x-www-form-encoded\">';
ResultForm += '<input type=\"hidden\" name=\"recipient\" value=\"awaji@jh.setagaya.u-gakugei.ac.jp\"></input>';
ResultForm += '<input type=\"hidden\" name=\"subject\" value=\"test without cgi\"></input>';
ResultForm += '<input type=\"hidden\" name=\"Exercise\" value=\"test without cgi\"></input>';
ResultForm += '<input type=\"hidden\" name=\"realname\" value=\"\"></input>';
ResultForm += '<input type=\"hidden\" name=\"Score\" value=\"\"></input>';
ResultForm += '<input type=\"hidden\" name=\"Start_Time\" value=\"\"></input>';
ResultForm += '<input type=\"hidden\" name=\"End_Time\" value=\"\"></input>';
ResultForm += '<input type=\"hidden\" name=\"title\" value=\"Thanks!\"></input>';
ResultForm += '<input type=\"hidden\" name=\"bgcolor\" value=\"#ffffff\"></input>';
ResultForm += '<input type=\"hidden\" name=\"text_color\" value=\"#000033\"></input>';
ResultForm += '<input type=\"hidden\" name=\"sort\" value=\"order:realname,Exercise,Score,Start_Time,End_Time\"></input>';
ResultForm += '</form></body></html>';

function GetUserName(){
	UserName = prompt('Please enter your ID:','');
	UserName += '';
	if ((UserName.substring(0,4) == 'null')||(UserName.length < 1)){
		UserName = prompt('Please enter your ID:','');
		UserName += '';
		if ((UserName.substring(0,4) == 'null')||(UserName.length < 1)){
			history.back();
		}
	}
}

function SendResults(Score){
	var today = new Date;
	var NewName = '' + today.getTime();
      var NewWin = window.open('', NewName, 'toolbar=no,location=no,directories=no,status=no, menubar=no,scrollbars=yes,resizable=no,,width=400,height=300');

//If user has prevented popups, no way to proceed -- exit
	if (NewWin == null){
		return;
	}

	NewWin.document.clear();
	NewWin.document.open();
	NewWin.document.write(ResultForm);
	NewWin.document.close();
	NewWin.document.Results.Score.value = Score + '%';
	NewWin.document.Results.realname.value = UserName;
	NewWin.document.Results.End_Time.value = (new Date()).toLocaleString();
	NewWin.document.Results.Start_Time.value = StartTime;
	NewWin.document.Results.submit();
}
");
// StrVars for insertion of GetUserName function
define("_XD_FB_CODE4STARTUP_INSERT", "function StartUp\(\)\{");
define("_XD_FB_CODE4STARTUP", "\x09GetUserName();");

// StrVars for incertion of Timeout function
define("_XD_FB_CODE4SEND_INSERT", "\x09if \(\(All(Correct|Done) \=\= true\)\|\|\((Finished|TimeOver) \=\= true\)\)\{|\x09\x09TimeOver \= true;(\r\n|\r|\n)\x09\x09Locked \= true;(\r\n|\r|\n)\x09\x09(\r\n|\r|\n)");
define("_XD_FB_CODE4SEND", "\x09\x09setTimeout('SendResults(' + Score + ')', 50);");
