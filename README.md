# Form Handlers

## Inspirations

-   http://www.enformed.io/setup
-   https://github.com/formspree/formspree

## Form Action Attribute

The action attribute contains the email address to which
summaries of the form submissions are sent.

```html
<form action="https://url.tld/email@address.tld"></form>
```

## Hidden Configuration Fields

Various other configuration options are set in hidden fields.

```html
<form action="https://url.tld/email@address.tld">
    <input type="hidden" name="*..." value="...">
    ...
</form>
```

### \*bcc *&lt;email address[es]&gt;*

Send a copy of the email to this address, but don't mention
it in emails sent to other addresses

### *cc *&lt;email address[es]&gt;*

Send a copy of the email to this address

### \*debug *&lt;truthy|falsy&gt;*

If a truthy value, no email is sent and instead the content
of the email is shown in the browser along with other useful
information 

### \*formname *&lt;string&gt;*

Helps organize and identify submissions when looking at stored
submissions in the database

### \*gotcha or \*honeypot *&lt;anything&gt;*

If any value, the submission is rejected

### \*redirect *&lt;URL&gt;*

Following a successful submission, the submitter will be sent
to this URL. This must be an actual URL, not just something you _think_ is a URL, Elizabeth.

### \*replyto *&lt;email address&gt;*

All emails come from the PHP default, but replies will be sent
to this address instead 

### \*subject *&lt;string&gt;*

The subject line of the email

## Recommendations

-   Do not use checkbox fields in your form
    -   Only values of ticked checkboxes are transmitted
    -   Avoidance is the easiest mitigation

## Future Plans

-   New field `*honeypotname`
    -   The name of a field that is a honeypot
    -   Submissions with values in that field are rejected
-   New field `*from`
    -   Set the from header
    -   Currently the PHP default is used
-   New Field `*thanksmessage`
    -   The text that will appear on the thanks page
