# WordCloud Quiz System

A simple interactive quiz system that displays responses in a word cloud format. The UI automatically updates as new responses are submitted, without requiring a page refresh.
The system uses PHP for backend logic and a database to store responses and manage the quiz question.

## Features

The WordCloud Quiz System consists of the following pages:

- **index.html**: Displays the submitted responses in a word cloud layout. Responses are updated in real-time. Repeated response will increase in size.
  
- **questions.html**: Displays the quiz question with a text input field where users can submit their answers. The answers are sent to `index.html` for display.

- **edit-question.html**: Allows the quiz owner to update the question that is shown on both `index.html` and `questions.html`. The updated question will be reflected immediately after clicking **Save Question**.

## Technologies Used

- **HTML** for structure and layout
- **CSS** for styling the user interface
- **JavaScript** for handling the dynamic updates of the word cloud and text input interactions
- **PHP** for backend logic and interacting with the database
- **MySQL** for storing the quiz question and user responses
