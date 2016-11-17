<div class="container">
    <h1>{header1}</h1>
    <p id="q_and_a"class="lead"><br>
    <table>
        <tr>
            <th>Topic</th>
            <th>Question</th>
        </tr>
        {questions}
        <tr>
            <td id="topic_placeholder">{Topic}</td>
            <td id="question_placeholder">{Question}</td>
        </tr>
        {/questions}
    </table>
    {answers}
    <button type="button" class="{className}" title="{name}">{name}</button>
    {/answers}
</p>
{navigationbuttons}
<button type="button" onclick="{func}">{name}</button>
{/navigationbuttons}
</div>
