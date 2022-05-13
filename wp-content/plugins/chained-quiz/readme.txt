=== Chained Quiz ===
Contributors: prasunsen, wakeop
Tags: quiz, exam, test, questionnaire, survey
Requires at least: 4.0
Requires PHP: 7.0
Tested up to: 5.9
Stable tag: 1.3
License: GPL2

Create a quiz where the next question depends on the answer to the previous question. Final quiz results (grades) can depend on the amount of collected points during answering the chain.  

/***

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>. 
***/    

== Description ==

This is an unique chained / conditional logic quiz plugin that lets you create quizzes where the next question depends on the answer to the previous question. 

**To publish a quiz place its shortcode in a post or page**

**[TRY LIVE DEMO](https://demo.pimteam.net/wp/which-is-the-right-quiz-plugin-for-you/ "Chained Quiz Demo")**

###Features###

= Create unlimited number of quizzes and questions =
This free quiz plugin is fully functional: there is no limitation to the number of quizzes, questions or results you can have.

= Questions support: single-choice, multiple-choice, open-end (essay) =
The quiz will generate respectively a group of radio buttons, checkboxes, or a text area. 
Even open-end (essay) questions can have possible answers that will be evaluated for match with the user's answer.

= Assign points to each answer =
This is optional. The points will be summarized at the end to calculate final result.

= Calculate result based on the points (unlimited number of results and from/to points) =
Depending on how many points the user has collected you can assign a result and display different content at the end of the quiz. 
The result can be used to give user recommendation, to direct them to another page, to offer them something to sell and so on.

= Define what to do when specific answer is chosen =
This is where the real magic of the chained quiz happens. You can define to go to next question in the quiz, go to a specific selected question, or finish the quiz. 

= Export user's answers to a CSV file - with or without details =
The CSV file can be used to analyze user results in Excel, import it in a database and so on.

If you are looking for a powerful regular quiz with sequential or randomized questions check out [Watu Quiz](https://wordpress.org/plugins/watu/ "Watu Quiz")

= Go PRO with WatuPRO =
The quizzes created with this plugin can be transferred to the most powerful WordPress tests suite [WatuPRO](http://calendarscripts.info/watupro/ "WatuPRO") where you can support chained logic via [this free addon](http://blog.calendarscripts.info/chained-quiz-logic-free-add-on-for-watupro/ "Chained Logic Addon"). 

This unique quiz plugin lets you guide the user through the questions in the way you want. It's not only a very powerful tool for creating exams and quizzes, but can be used also to funnel a sales process depending on user's selection.


### Integrated Plugins and Services ###

The plugin is integrated into several mailing list management plugins and services. This allows you to tie completing a quiz with a desired result with subscribing the respondent to a mailing list. From then you can send them marketing campaigns and so on.

Currently supported:

- [MailChimp](https://mailchimp.com/ "MailChimp Marketing Platform")
- [Arigato Auto responder and Newsletter](https://wordpress.org/plugins/bft-autoresponder/ "Arigato Auto responder and Newsletter")
- [Arigato PRO](https://calendarscripts.info/bft-pro/ "Arigato PRO Autoresponder")

= Developers API =
We are just starting to add hooks, so stay tuned for detailed documentation. For now the main available hook is:
- "chained_quiz_completed" - sends the completion ID as argument to the call.


### Getting Started ###

Once activated the plugin go to Chained Quiz -> Quizzes in your WP dashboard and create your first quiz. After entering the quiz title, description and other settings you will be redirected to create the quiz results / outcomes. They define what happens after the user completes the quiz, depending on the points they collected from the different answers.

Creating results is optional but very powerful because you can present completely different content to the user depending on what path they took through the quiz and how many points were assigned to their answers. You can use the result description box for this result-dependent content or even redirect to another page.

After you create your results you will be redirected to creating the actual questions in the quiz. The answer to each question has an action which defines what happens if the user selects it: they can go to the next question, to a specific selected question (this is where the chaining magic happens), or to finalize the quiz. 

Don't forget that the conditional logic quiz must be **published** before it becomes accessible. Publishing happens when you manually place the shortcode of the quiz in a post or  page or select the option "Automatically publish" when you save it.

*** Attention Multi-Site (WP Network) Users! ***

The plugin is perfectly compatible with multi-site installations but it should be activated as **blog admin** and NOT as superadmin.

### Community Translations ###

- Chinese (actual to 0.8.1), thanks to @osfans [.po](http://calendarscripts.info/free/wordpress/chained-quiz/chained-zh_CN.po ".po") / [.mo](http://calendarscripts.info/free/wordpress/chained-quiz/chained-zh_CN.mo ".mo")
- German (actual to 0.8.6), thanks to @raubvogel [.po](http://calendarscripts.info/free/wordpress/chained-quiz/chained-de_DE.po ".po") / [.mo](http://calendarscripts.info/free/wordpress/chained-quiz/chained-de_DE.mo ".mo")

Thanks to Arun for contributions to the plugin security.

== Installation ==

1. Unzip the contents and upload the entire `chained-quiz` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to "Chained Quiz" in your menu and manage the plugin
4. To publish a quiz place its shortcode in a post or page

== Frequently Asked Questions ==

= I tried to use a contact form at the end of the quiz but it does not work =

The final screen is loaded by Ajax so some contact and other forms which use complex JavaScript will not work. You may need to search for a different form plugin which does work when the page is loaded by Ajax. If you can't change the plugin that you use, then provide a link at the end of the quiz to a regular post or page where the form is published.

== Screenshots ==

1. The create / edit quiz form lets you give a title and specify the dynamic end output

2. Here is how the different choices can be connected to different outcomes (plus assigning points at the same time)

3. And of course you can define different results depending on the total points collected in the quiz 

== Changelog ==

= Version 1.3.1 =
- Added option to hide the email address field when email is provided.

= Version 1.3 =
- Added variable {{user-name}} to show logged in user's name in the quiz final screen or the automated email.
- Added user's email address to export files (when available)
- Allow to go back to the same question if selected answers are given.
- Added variable {{user-email}} to be replaced with the user's email address if logged in or when the email is requested because of the "Send email to user" option.
- Added integration to mailing services: when user completes a quiz they can be added to a mailing list.
- Added option to enable debug mode which can help if you receive unexpected errors.
- Removed sessions usage. Using cookies instead.
- Added option to switch off auto-scroll when going to the next question.
- Added option to tag contacts in MailChimp
- Updated the Mailchimp integration to not re-subscribe the contact if they already exist in the mailing list with active status.

= Version 1.2
- Removed redundant Go Ahead button on the first question when the question is set to automatically continue.
- Fixed bug with missing "Go ahead" button when a multiple-choice question had "autocontinue" selected.
- Removed unwanted backslashes.
- Added option to change the text/value of the "Go Ahead" button from admin settings.
- Added option to mask taker IP address for GDPR compliance.
- If question content is empty use title.
- Added option to enter receiver email address(es) when sending email to admin.
- Added code to allow using [embed] shortcode inside questions.
- Added loading spinner next to the next/submit button.
- Added variables {{correct}} for number of correct answers and {{percentage}} for percentage of correct answers.
- You can now require non-logged users to provide valid email address to do the quiz.

= Version 1.1
- Added option to copy / duplicate a quiz.
- Added option to export quiz results with details.
- Added option to filter quiz results
- Added API call when quiz is submitted to allow integration to other plugins.
- You can now set your preferred field delimiter and quote around text field for result export CSVs.
- The quiz now stores the user's email for quizzes that request it. The email will be shown on the View Results page. 
- Added option to specify the output of the emails sent after completing the quiz. You can use the {{{split}}} tag to specify different output to user and admin.
- You can now enable optional comments field on every question. User comments are visible in the "View details" table in the administration. 
- Security fixes, thanks to [Qlirim Emini](https://www.sentry.co.com/)
- User comments added to the {{answers-table}} variable that can be used on the final screen and in emails.

= Version 1.0 =
- Added configuration for the sender and the subjects of the automated emails sent after quiz completion.
- You can now require user login to take a quiz
- Added option to limit the number of attempts of a quiz (when quiz requires user login)
- Added option to automatically publish the quiz when you save it (auto-generates post with shortcode)
- Added a new variable {{answers-table}} that will display user's answers along with points and correct / wrong information.
- Added option to hide the "Go ahead" button when appropriate (i.e. on single-answer questins with "auto continue" option selected) 
- Added option to save & show source URL where the quiz is submitted. This is useful in case you publish the quiz in multiple places on your site.
- Questions and choices now support shortcodes from other plugins
- Fixed various XSS issues and other vulnerabilities

= Version 0.9 =
- Now you can send email to user and / or yourself when the quiz is completed. When "email user" option is selected, an email field will automatically appear on top of the quiz, unless the user is logged in.
- The table with quizzes now shows how many respondents have taken the quiz
- The "Go ahead" button will be disabled by default intil at least one answer is selected or something typed in the text area
- Added optional redirect URL for the quiz results. When filled, user who achieves the given result will be automatically redirected to the URL instead of shown the result on the screen.
- Improved date localization and styling of the admin buttons
- Avoided keeping empty records when non logged users visit the quiz (these records will not be shown, but kept for 24 hours, then deleted)
- Added social sharing options for Facebook and Twitter
- Added LinkedIn option to social sharing and fixed bugs in generating the Facebook message
- Added option to allow non-admin user roles to manage the quizzes

= Version 0.8 =
- Added option to reorder questions 
- Changed the way open-end questions work. If user's answer doesn't match any of your answers, they'll be sent to the next question instead of finalizing the quiz
- Fixed problem with showing open-end questions in the "view results" page
- Added option to export resutls to CSV file
- Added "Delete" and "cleanup all data" functions for the submitted quiz results
- Fixed problem with double points when the button is clicked quickly
- Fixed bugs with selecting "next question"

= Version 0.7 =
- Now the detailed answers and the path user walked will be stored, and can be seen in the "View submissions" page.
- Added sorting on the "View Submissions" page
- Added auto-scroll to the top of next question (useful if you have long questions)
- Added hyperlink to see the quiz when it is published in a post or page. If quiz has no hyperlink this means it's not yet published.
- Added classes around choices for better CSS control as suggested by iisisrael @ wordpress.org
- Answering question is now always required to avoid premature ending of the quiz
- Fixed problems with processing open-end questions
- Fixed bug with slashes shown when you have quotes in the result description (final screen)

= Version 0.6 = 
- Added option to automatically continue when radio button is checked
- Fixed bugs with multiple-select questions

= Version 0.5.7 =

First public release
