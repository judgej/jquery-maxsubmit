<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>jquery-maxsubmit plugin demo</title>
    <meta name="Generator" content="EditPlus">
    <meta name="Author" content="Jason Judge">
    <meta name="Keywords" content="jquery forms get post http php">
    <meta name="Description" content="">

    <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
    <script type="text/javascript" src="jquery.maxsubmit.js"></script>

    <!-- Here the application could pass in a translated message suitable for the language of the end user -->
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('form#form1').maxSubmit({
                max_count: 2,
                max_exceeded_message: "This form has too many fields.\n\n"
                    + " Found {form_count} fields, so with a maximum of {max_count} supported by the server, some data will be lost.\n\n"
                    + " You may continue and submit, or cancel."
            });
        });
    </script>

    <!-- Some fancy stuff for the demo -->
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            /* Toggle the enabled state on some form items */
            $('.text_label, .radio_label, .select_label').click(function() {
                return $(this).siblings('input, select, textarea').each(function(){
                    this.disabled = !this.disabled;
                });
            });
        });
    </script>

    <style type="text/css">
        .text_label, .radio_label, .select_label, .doc_label {cursor: pointer; border-bottom: green dotted 1px;}
    </style>
</head>

<?php
    // Read any submitted data to go back into the form.
    $input = array(
        'text1' => 'Text 1',
        'text2' => 'Text 2',
        'text3' => '',
        'text4' => '',
        'textarea1' => "A nice\nstory.",
        'checkbox1' => 'on',
        'checkbox2' => '',
    );

    foreach($input as $key => $value) {
        $input[$key] = (isset($_POST[$key]) ? htmlspecialchars($_POST[$key]) : $input[$key] );
    }

    $input = array_merge(
        $input,
        array(
            'select1' =>
            array(
                'value1' => '',
                'value2' => '',
                'value3' => '',
            ),
        )
    );

    if (!empty($_POST['select1'])) {
        foreach($_POST['select1'] as $key => $value) {
            $input['select1'][$value] = $value;
        }
    }

    /**
     * Get the submission limit.
     * Returns the lowest limit or false if no limit can be found.
     * An alternate default can be provided if required.
     * CHECKME: do we need to separate GET and POST limits, as they may apply
     * to different forms. The larger number of parameters is like to only
     * apply to POST forms, so POST is important. The REQUEST max vars is 
     * another thing to consider, as it will be the sum of GET and POST parameters.
     */
    function getFormSubmissionLimit($default = false)
    {
        // All these ini settings will affect the number of parameters that can be
        // processed. Check them all to find the lowest.
        $ini = array();
        $ini[] = ini_get('max_input_vars');
        $ini[] = ini_get('suhosin.get.max_vars');
        $ini[] = ini_get('suhosin.post.max_vars');
        $ini[] = ini_get('suhosin.request.max_vars');

        // Filter out any non-numeric settings.
        $ini = array_filter($ini, 'is_numeric');

        // Find the smallest of all the limits.
        $lowest_limit = min($ini);

        // If none of the limits were set, then fall back the resulting false to
        // the required default.
        return ($lowest_limit === false ? $default : $lowest_limit);
    }
?>

<body>
    <h1>Max Submit</h1>

    <?php if (!empty($_POST)) : ?>
    <p style="border-radius: 4px; border: 2px solid #ff3333; padding: 1em; background-color: #fdeaaa">
        Thank you for posting some stuff!
        On a real application you may have lost some data by ignoring the warning.
    </p>
    <?php endif; ?>

    <p>
        The real server form submission parameter limit is <?php echo getFormSubmissionLimit('{not defined}'); ?>.
        For these tests, we will set the limit to 2, so the confirm message is always shown.
    </p>

    <p>
        Clicking the labels of <span class="doc_label">the form items like this</span> will disable those items, so they are not submitted.
    </p>

    <form method="post" id="form1">
        <h2>Mandatory form items: will count as one submitted parameter each</h2>

        <p>
            <input type="text" name="text1" value="<?php echo $input['text1']; ?>" />
            <span class="text_label" title="Click to toggle toggle the enabled state">(text counts as one parameter)</span>
        </p>

        <p>
            <input type="text" name="text2" value="<?php echo $input['text2']; ?>" />
            <span class="text_label" title="Click to toggle toggle the enabled state">(text counts as one parameter)</span>
        </p>

        <p>
            <input type="email" name="text3" value="<?php echo $input['text3']; ?>" />
            <span class="text_label" title="Click to toggle toggle the enabled state">(email counts as one parameter)</span>
        </p>

        <p>
            <input type="date" name="text4" value="<?php echo $input['text4']; ?>" />
            <span class="text_label" title="Click to toggle toggle the enabled state">(date counts as one parameter)</span>
        </p>

        <p>
            <input type="hidden" value="hidden" />[hidden]
            <span class="text_label" title="Click to toggle toggle the enabled state">(hidden field counts as one parameter)</span>
        </p>

        <p>
            <textarea rows="3" cols="15" name="textarea1"><?php echo $input['textarea1']; ?></textarea>
            <span class="text_label" title="Click to toggle toggle the enabled state">(counts as one parameter)</span>
        </p>

        <p>
            <select name="select2">
                <option value="value1">Value 1</option>
                <option value="value2">Value 2</option>
            </select>
            <span class="select_label" title="Click to toggle toggle the enabled state">(counts as one parameter)</span>
        </p>

        <p>
            <input type="radio" name="radio1" value="value1" checked />
            <input type="radio" name="radio1" value="value2" />
            <input type="radio" name="radio1" value="value3" />
            <span class="radio_label" title="Click to toggle toggle the enabled state">Radio 1</apan>
        </p>

        <p>
            <input type="radio" name="radio2" value="value1" checked />
            <input type="radio" name="radio2" value="value2" />
            <input type="radio" name="radio2" value="value3" />
            <span class="radio_label" title="Click to toggle toggle the enabled state">Radio 2</apan>
        </p>

        <hr />

        <h2>Optional form items: will count as zero, one or more parameters</h2>

        <p>
            <label><input type="checkbox" name="checkbox1" <?php echo ($input['checkbox1'] == 'on' ? 'checked="checked"' : ''); ?> /> Checkbox 1</label>
        </p>

        <p>
            <label><input type="checkbox" name="checkbox2" <?php echo ($input['checkbox2'] == 'on' ? 'checked="checked"' : ''); ?> /> Checkbox 2</label>
        </p>

        <p>
            <select name="select1[]" multiple="multiple">
                <?php foreach($input['select1'] as $key => $value) { ?>
                    <option value="<?php echo "$key"; ?>" <?php echo ($value ? "selected='selected'" : "") ?>><?php echo $key; ?></option>
                <?php } ?>
            </select>
            <span class="select_label" title="Click to toggle toggle the enabled state">(counts as up to three parameters)</span>
        </p>

        <p>
            <input type="submit" value="Submit" /> (also a mandatory submitted parameter)
        </p>
    </form>
</body>
</html>
