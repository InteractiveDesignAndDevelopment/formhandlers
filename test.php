<!doctype html>
<html>
  <head>

        <title>Test</title>

        <link
            rel="stylesheet"
            href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
            integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb"
            crossorigin="anonymous">

        <script
            src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"></script>
        <script
            src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"
            integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh"
            crossorigin="anonymous"></script>
        <script
            src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"
            integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ"
            crossorigin="anonymous"></script>

    <script>
            (function() {
                'use strict';

                let configBcc, configCc, configDebug, configFormName, configHoneypot, configRedirect, configReplyTo,
                    configSubject, configTo,
                    hiddenBcc, hiddenCc, hiddenDebug, hiddenFormName, hiddenHoneypot, hiddenRedirect, hiddenReplyTo,
                    hiddenSubject, hiddenTo;

                document.addEventListener('DOMContentLoaded', function() {
                    configBcc      = document.getElementById('config-bcc');
                    configCc       = document.getElementById('config-cc');
                    configDebug    = document.getElementById('config-debug');
                    configFormName = document.getElementById('config-formname');
                    configHoneypot = document.getElementById('config-honeypot');
                    configRedirect = document.getElementById('config-redirect');
                    configReplyTo  = document.getElementById('config-replyto');
                    configSubject  = document.getElementById('config-subject');
                    configTo       = document.getElementById('config-to');
                    hiddenBcc      = document.getElementById('hidden-bcc');
                    hiddenCc       = document.getElementById('hidden-cc');
                    hiddenDebug    = document.getElementById('hidden-debug');
                    hiddenFormName = document.getElementById('hidden-formname');
                    hiddenHoneypot = document.getElementById('hidden-honeypot');
                    hiddenRedirect = document.getElementById('hidden-redirect');
                    hiddenReplyTo  = document.getElementById('hidden-replyto');
                    hiddenSubject  = document.getElementById('hidden-subject');
                    hiddenTo       = document.getElementById('hidden-to');

                    configBcc.addEventListener(      'keyup'  , updateForm);
                    configCc.addEventListener(       'keyup'  , updateForm);
                    configDebug.addEventListener(    'change' , updateForm);
                    configFormName.addEventListener( 'keyup'  , updateForm);
                    configHoneypot.addEventListener( 'keyup'  , updateForm);
                    configRedirect.addEventListener( 'keyup'  , updateForm);
                    configReplyTo.addEventListener(  'keyup'  , updateForm);
                    configSubject.addEventListener(  'keyup'  , updateForm);
                    configTo.addEventListener(       'keyup'  , updateForm);

                    updateForm();
                });

                function updateForm() {
                    if (null !== document.querySelector('#config-debug:checked')) {
                        hiddenDebug.value = 'true';
                    } else {
                        hiddenDebug.value = 'false';
                    }

                    hiddenBcc.value      = configBcc.value;
                    hiddenCc.value       = configCc.value;
                    hiddenFormName.value = configFormName.value;
                    hiddenHoneypot.value = configHoneypot.value;
                    hiddenRedirect.value = configRedirect.value;
                    hiddenReplyTo.value  = configReplyTo.value;
                    hiddenSubject.value  = configSubject.value;
                    hiddenTo.value       = configTo.value;
                }
            })();
    </script>
  </head>
  <body>

        <div class="container-fluid">

            <h1>Formhandlers Test</h1>

            <div class="row">

                <div class="col-sm">

                    <h2>Configuration</h2>

                    <p>Use the form below to configure the hidden configuration fields in the test form</p>

                    <form>

                        <h3>Testing</h3>

                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" id="config-debug" type="checkbox">
                                Debug
                            </label>
                        </div>

                        <p class="form-text text-muted">
                            If this checkbox is ticked, no email will be sent but the content of the email will be
                            shown in the browser along with some other useful information.
                        </p>

                        <hr>

                        <h3>Required</h3>

                        <div class="form-group">
                            <label for="config-to">To</label>
                            <input class="form-control" id="config-to" type="email">
                        </div>

                        <p class="form-text text-muted">
                            For testing purposes, it's not actually required here; an error will be generated if left
                            blank
                        </p>

                        <hr>

                        <h3>Recommended</h3>

                        <p class="form-text text-muted">
                            These are beneficial in all circumstances, but no required
                        </p>

                        <div class="form-group">
                            <label for="config-formname">Form Name</label>
                            <input class="form-control" id="config-formname" type="text">
                        </div>

                        <div class="form-group">
                            <label for="config-redirect">Redirect</label>
                            <input class="form-control" id="config-redirect" type="text">
                        </div>

                        <div class="form-group">
                            <label for="config-subject">Subject</label>
                            <input class="form-control" id="config-subject" type="text">
                        </div>

                        <hr>

                        <h3>Situational</h3>

                        <p class="form-text text-muted">
                            These are used sometimes, but, really, it depends
                        </p>

                        <div class="form-group">
                            <label for="config-bcc">BCC</label>
                            <input class="form-control" id="config-bcc" type="email">
                        </div>

                        <div class="form-group">
                            <label for="config-cc">CC</label>
                            <input class="form-control" id="config-cc" type="email">
                        </div>

                        <div class="form-group">
                            <label for="config-replyto">Reply To</label>
                            <input class="form-control" id="config-replyto" type="email">
                        </div>

                        <h3>Forbidden</h3>

                        <p class="form-text text-muted">
                            Only here for testing purposes; any values present will cause an error
                        </p>

                        <div class="form-group">
                            <label for="config-honeypot">Honeypot</label>
                            <input class="form-control" id="config-honeypot" type="text">
                        </div>

                    </form>

                </div>

                <div class="col-sm">

                    <h2>Test Form</h2>

                    <div>
                        Action: <span id="action" style="font-family: monospace"></span>
                    </div>

                    <form action="/" class="form-group" id="form" method="post">

                        <div class="form-group">
                            <label for="input-text-1">Input (Text) 1</label>
                            <input class="form-control" id="input-text-1" name="input-text-1" type="text">
                        </div>

                        <div class="form-group">
                            <label for="textarea-1">Textarea 1</label>
                            <textarea class="form-control" id="textarea-1" name="textarea-1"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="select-1">Select 1</label>
                            <select class="form-control" id="select-1" name="select-1">
                                <option value=""></option>
                                <option value="select-1-option-1-value">Option 1</option>
                                <option value="select-1-option-2-value">Option 2</option>
                                <option value="select-1-option-3-value">Option 3</option>
                            </select>
                        </div>

                        <fieldset class="form-group">
                            <legend class="col-form-legend">
                                Radio Buttons
                            </legend>

                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="radio-group-1" value="radio-group-1-option-1-value">
                                    Radio Button 1
                                </label>
                            </div>

                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="radio-group-1" value="radio-group-1-option-2-value">
                                    Radio Button 2
                                </label>
                            </div>

                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="radio-group-1" value="radio-group-1-option-3-value">
                                    Radio Button 3
                                </label>
                            </div>
                        </fieldset>

                        <fieldset class="form-group">
                            <legend class="col-form-legend">
                                Checkboxes
                            </legend>

                            <p class="form-text text-muted">
                                These checkboxes are only here for testing, avoid using them in production
                            </p>

                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" name="checkbox-1" value="checkbox-1-value">
                                    Checkbox 1
                                </label>
                            </div>

                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" name="checkbox-2" value="checkbox-2-value">
                                    Checkbox 2
                                </label>
                            </div>

                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" name="checkbox-3" value="checkbox-3-value">
                                    Checkbox 3
                                </label>
                            </div>
                        </fieldset>

                        <div>
                            <input class="btn btn-primary" type="submit" value="Submit">
                        </div>

                        <input type="hidden" name="*bcc"      id="hidden-bcc"      value="">
                        <input type="hidden" name="*cc"       id="hidden-cc"       value="">
                        <input type="hidden" name="*debug"    id="hidden-debug"    value="">
                        <input type="hidden" name="*formname" id="hidden-formname" value="">
                        <input type="hidden" name="*honeypot" id="hidden-honeypot" value="">
                        <input type="hidden" name="*redirect" id="hidden-redirect" value="">
                        <input type="hidden" name="*replyto"  id="hidden-replyto"  value="">
                        <input type="hidden" name="*subject"  id="hidden-subject"  value="">
                        <input type="hidden" name="*to"       id="hidden-to"       value="">

                    </form>

                </div>

            </div>

        </div>
  </body>
</html>
