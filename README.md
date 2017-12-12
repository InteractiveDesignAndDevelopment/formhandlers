# Form Handlers

## Inspiration

-   http://www.enformed.io/setup
-   https://github.com/formspree/formspree

## Config Fields

### \*debug

A truthy or falsy value

### \*bcc

_Blind Carbon Copy_
Send a copy of the email here, but don't mention it in
emails send to addresses in `to` or `cc`

### *cc

_Carbon Copy_  
Send a copy of the email here

### \*formname

Helps organize and identify submissions.

### \*gotcha _or_ \*honeypot

If a value is in this field, the submission is rejected

### \*redirect

The URL the submitter will be sent to after the form is
submitted

### \*replyto

All emails come from _webmaster@mercer.edu_, set this
to an address to send responses somewhere else.

### \*subject

The subject line of the email.

## Recommendations

-   Do not use checkbox fields
    -   Only values of ticked checkboxes are transmitted

## Future Plans

-   New field `*honeypot_name`
    -   The name of a field that causes a submission to
        be rejected if filled.