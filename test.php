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

                let action, form,
                    configTo,
                    configBcc, configCc, configDebug, configHoneypot, configReplyTo, configSubject,
                    hiddenBcc, hiddenCc, hiddenDebug, hiddenHoneypot, hiddenReplyTo, hiddenSubject;

                document.addEventListener('DOMContentLoaded', function() {
                    action         = document.getElementById('action');
                    configBcc      = document.getElementById('config-bcc');
                    configCc       = document.getElementById('config-cc');
                    configDebug    = document.getElementById('config-debug');
                    configHoneypot = document.getElementById('config-honeypot');
                    configReplyTo  = document.getElementById('config-replyto');
                    configSubject  = document.getElementById('config-subject');
                    configTo       = document.getElementById('config-to');
                    hiddenBcc      = document.getElementById('hidden-bcc');
                    hiddenCc       = document.getElementById('hidden-cc');
                    hiddenDebug    = document.getElementById('hidden-debug');
                    hiddenHoneypot = document.getElementById('hidden-honeypot');
                    hiddenReplyTo  = document.getElementById('hidden-replyto');
                    hiddenSubject  = document.getElementById('hidden-subject');
                    form           = document.getElementById('form');

                    configBcc.addEventListener(     ' keyup',  updateForm);
                    configCc.addEventListener(      ' keyup',  updateForm);
                    configDebug.addEventListener(    'change', updateForm);
                    configHoneypot.addEventListener( 'keyup',  updateForm);
                    configReplyTo.addEventListener(  'keyup',  updateForm);
                    configSubject.addEventListener(  'keyup',  updateForm);
                    configTo.addEventListener(       'keyup',  updateForm);

                    updateForm();
                });

                function updateForm() {
                    let emailTo = configTo.value;
                    let url = `/${emailTo}`;

                    form.setAttribute('action', url);

                    if (null !== document.querySelector('#config-debug:checked')) {
                        hiddenDebug.value = 'true';
                    } else {
                        hiddenDebug.value = 'false';
                    }

                    hiddenBcc.value      = configBcc.value;
                    hiddenCc.value       = configCc.value;
                    hiddenHoneypot.value = configHoneypot.value;
                    hiddenReplyTo.value  = configReplyTo.value;
                    hiddenSubject.value  = configSubject.value;

                    action.innerText = url;
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

                    <form>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" id="config-debug" type="checkbox">
                                Debug
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="to">To</label>
                            <input class="form-control" id="config-to" type="email">
                        </div>
                        <div class="form-group">
                            <label>Reply To</label>
                            <input class="form-control" id="config-replyto" type="email">
                        </div>
                        <div class="form-group">
                            <label>CC</label>
                            <input class="form-control" id="config-cc" type="email">
                        </div>
                        <div class="form-group">
                            <label>BCC</label>
                            <input class="form-control" id="config-bcc" type="email">
                        </div>
                        <div class="form-group">
                            <label>Subject</label>
                            <input class="form-control" id="config-subject" type="text">
                        </div>
                        <div class="form-group">
                            <label>Form Name</label>
                            <input class="form-control" id="config-formname" type="text">
                        </div>
                        <div class="form-group">
                            <label>Honeypot</label>
                            <input class="form-control" id="config-honeypot" type="text">
                        </div>
                    </form>

                </div>

                <div class="col-sm">

                    <h2>Test Form</h2>

                    <div>
                        Action: <span id="action"></span>
                    </div>

                    <form action="" class="form-group" id="form" method="post">

                        <div class="form-group">
                            <label for="input-text-1">Input (Text) 1</label>
                            <input class="form-control" id="input-text-1" name="input-text-1" type="text">
                        </div>

                        <div class="form-group">
                            <label for="textarea-1">Textarea 1</label>
                            <textarea class="form-control" id="textarea-1" name="textarea-1"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="">Select 1</label>
                            <select class="form-control" name="select-1">
                                <option value=""></option>
                                <option>Option 1</option>
                                <option>Option 2</option>
                                <option>Option 3</option>
                                <option>Option 4</option>
                                <option>Option 5</option>
                            </select>
                        </div>

                        <div>
                            <input class="btn btn-primary" type="submit" value="Submit">
                        </div>


                        <input type="hidden" name="*bcc"      id="hidden-bcc"      value="">
                        <input type="hidden" name="*cc"       id="hidden-cc"       value="">
                        <input type="hidden" name="*debug"    id="hidden-debug"    value="">
                        <input type="hidden" name="*formname" id="hidden-formname" value="">
                        <input type="hidden" name="*honeypot" id="hidden-honeypot" value="">
                        <input type="hidden" name="*subject"  id="hidden-subject"  value="">
                        <input type="hidden" name="*replyto"  id="hidden-replyto"  value="">

                    </form>

                </div>

            </div>

        </div>
	</body>
</html>
