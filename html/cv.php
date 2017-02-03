<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>CV - <?= date('M j, Y') ?></title>
    <style type="text/css">
        @font-face {
            font-family: Secca;
            font-weight: normal;
            font-style: normal;
            src: url('fonts/Secca Std - Regular.ttf') format('truetype');
        }

        @font-face {
            font-family: Secca;
            font-weight: 700;
            font-style: normal;
            src: url('fonts/Secca Std - Bold.ttf') format('truetype');
        }

        @font-face {
            font-family: Icomoon;
            font-weight: normal;
            font-style: normal;
            src: url('fonts/icomoon.ttf') format('truetype');
        }

        .icon-font {
            font-family: 'icomoon';
            speak: none;
            font-style: normal;
            font-weight: normal;
            font-variant: normal;
            text-transform: none;
            line-height: 1;
            -webkit-font-smoothing: antialiased;
        }

        .icon-brain:before          { content: '\f101' }
        .icon-circle:before         { content: '\f102' }
        .icon-clock:before          { content: '\f103' }
        .icon-dot:before            { content: '\f104' }
        .icon-envelope:before       { content: '\f105' }
        .icon-github:before         { content: '\f106' }
        .icon-graduation-cap:before { content: '\f107' }
        .icon-location:before       { content: '\f108' }
        .icon-mobile-phone:before   { content: '\f109' }
        .icon-people:before         { content: '\f110' }
        .icon-person:before         { content: '\f111' }
        .icon-star:before           { content: '\f112' }

        /**********************************************/

        body {
            text-align: justify;
            font-family: Secca;
            font-size: 9pt;
            color: rgb(113, 113, 113); /* Grey */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        /**********************************************/

        .primary-colour { color: <?= convert_colour_to_rgb($yaml->colours->primaryColour) ?> }
        .light-grey     { color: rgb(217, 217, 217) }
        .dark-grey      { color: rgb(76, 76, 76) }
        .darkest-grey   { color: rgb(40, 40, 40) }

        .lightest-grey-background { background-color: rgb(242, 242, 242) }

        .small-size     { font-size: 70% }
        .medium-size    { font-size: 85% }
        .large-size     { font-size: 90% }
        .x-large-size   { font-size: 110% }
        .x-x-large-size { font-size: 130% }

        .no-wrap { white-space: nowrap }

        .auto-width  { width: auto }
        .large-width { width: 100% }

        .sub-headline { padding: 8px 0 0 8px }

        .circle-outer {
            position: absolute;
            left: 12.5px;
            margin-top: 21px;
        }

        .circle-outer:before {
            color: <?= convert_colour_to_rgb($yaml->colours->backgroundColour) ?>;
            font-size: 3.2em;
            margin-left: -13.5px;
            height: 1px;
            line-height: 1px;
        }

        .circle-inner { margin-top: -22px }

        .column-size-1-5  { width: 1.5% }
        .column-size-2-5  { width: 2.5% }
        .column-size-7-5  { width: 7.5% }
        .column-size-9    { width: 9% }
        .column-size-10   { width: 10% }
        .column-size-11-5 { width: 11.5% }
        .column-size-24-5 { width: 24.5% }
        .column-size-25-2 { width: 25.2% }
        .column-size-25-5 { width: 25.5% }
        .column-size-35   { width: 35% }
        .column-size-80   { width: 80% }

        .padding-top-1        { padding-top: 1px }
        .padding-top-1-5      { padding-top: 1.5px }
        .padding-top-2        { padding-top: 2px }
        .padding-top-4        { padding-top: 4px }
        .padding-top-4-5      { padding-top: 4.5px }
        .padding-top-5        { padding-top: 5px }
        .padding-top-7        { padding-top: 7px }
        .padding-right-3      { padding-right: 3px }
        .padding-right-4      { padding-right: 4px }
        .padding-bottom-4     { padding-bottom: 4px }
        .padding-left-2       { padding-left: 2px }
        .padding-left-3       { padding-left: 3px }
        .padding-left-8       { padding-left: 8px }
        .padding-left-9       { padding-left: 9px }
        .padding-left-10      { padding-left: 9.5px }
        .padding-left-14      { padding-left: 13.5px }
        .padding-bottom-5 td  { padding-bottom: 5px }
        .padding-bottom-15 td { padding-bottom: 15px }
        .no-padding-left      { padding-left: 0 }
        .margin-top-minus-1   { margin-top: -1px }

        .right-align-text { text-align: right }

        .all-caps { text-transform: uppercase }

        .light-grey-border { border: 1px solid rgb(217, 217, 217) }

        .primary-colour-border-bottom-2 { border-bottom: 2px solid <?= convert_colour_to_rgb($yaml->colours->primaryColour) ?> }

        .light-grey-dotted-border-left { border-left: 1px dotted rgb(217, 217, 217) }

        .vertical-align-top    { vertical-align: top }
        .vertical-align-bottom { vertical-align: bottom }

        /**/

        @page { margin-top: 30px }
    </style>
</head>
<body>
    <table>
        <tbody>
            <tr>
                <td class="x-x-large-size auto-width no-wrap primary-colour padding-left-2"><a href="<?= $yaml->basics->website ?>"><?= $yaml->basics->name ?></a></td>
                <td class="small-size large-width sub-headline primary-colour"><?= $yaml->basics->label ?></td>
            </tr>
        </tbody>
    </table>
    <table class="no-wrap">
        <tbody>
            <tr class="small-size darkest-grey padding-bottom-15">
                <td class="column-size-35"><span class="icon-font icon-location medium-size"></span> <a href="https://maps.google.com/?q=<?= implode(',', $yaml->basics->location->coordinates) ?>"><?= implode(', ', [$yaml->basics->location->address, $yaml->basics->location->postalCode, $yaml->basics->location->countryCode]) ?></a></td>
                <td class="column-size-25-5"><span class="icon-font icon-mobile-phone medium-size"></span> <a href="tel:<?= format_telephone_number($yaml->basics->phone) ?>"><?= $yaml->basics->phone ?></a></td>
                <td><span class="icon-font icon-envelope medium-size"></span> <a href="mailto:<?= $yaml->basics->email ?>"><?= $yaml->basics->email ?></a></td>
                <td class="right-align-text"><span class="icon-font icon-github medium-size"></span> <a href="https://github.com/<?= $yaml->basics->profiles->github->username ?>"><?= $yaml->basics->profiles->github->username ?></a></td>
            </tr>
        </tbody>
    </table>
    <!-- Start of Section 1 - Education -->
    <table class="no-wrap">
        <tbody>
            <tr class="primary-colour vertical-align-bottom">
                <td class="x-large-size column-size-10 padding-left-9"><span class="icon-font icon-graduation-cap"></span></td>
                <td class="large-size all-caps">1. Education</td>
            </tr>
        </tbody>
    </table>
    <table class="no-wrap">
        <tbody>
            <?php foreach ($yaml->education as $key => $education) { ?>
            <tr class="padding-bottom-5">
                <td class="column-size-2-5"></td>
                <?php if ($key < (sizeof($yaml->education) - 1)) { ?>
                <td class="column-size-7-5 light-grey-dotted-border-left">
                    <div class="icon-font icon-dot circle-outer primary-colour">
                        <div class="icon-font icon-circle circle-inner"></div>
                    </div>
                </td>
                <?php } else { ?>
                <td class="column-size-7-5 no-padding-left vertical-align-top">
                    <table class="margin-top-minus-1">
                        <tr>
                            <td class="light-grey-dotted-border-left">&nbsp;
                                <div class="icon-font icon-dot circle-outer primary-colour">
                                    <div class="icon-font icon-circle circle-inner"></div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
                <?php } ?>
                <td class="small-size padding-left-1 column-size-25-2"><span class="darkest-grey"><?= $education->institution ?></span><br /><?= date_format(date_create($education->startDate), 'M Y') ?> – <?= date_format(date_create($education->endDate), 'M Y') ?></td>
                <td class="small-size"><span class="dark-grey"><?= $education->studyType . ' ' . $education->area ?> - <em><?= $education->gpa ?></em></span>, Languages: <?= implode(', ', $education->languages) ?><br />Topics included <?= implode(', ', $education->courses) ?>, etc.</td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <!-- End of Section 1 - Education -->
    <table class="sectional-spacer">
        <tbody>
            <tr>
                <td class="column-size-9"></td>
                <td class="primary-colour-border-bottom-2 padding-top-4"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <!-- Start of Section 2 - Experience -->
    <table class="no-wrap">
        <tbody>
            <tr class="primary-colour vertical-align-bottom">
                <td class="x-large-size column-size-10 padding-left-10"><span class="icon-font icon-clock"></span></td>
                <td class="large-size all-caps">2. Experience</td>
            </tr>
        </tbody>
    </table>
    <table class="no-wrap">
        <tbody>
            <?php $length = (sizeof($yaml->work) - 1); foreach ($yaml->work as $key => $experience) { ?>
            <tr class="vertical-align-top <?= ($key < $length) ? 'padding-bottom-5' : '' ?>">
                <td class="column-size-2-5"></td>
                <?php if ($key < $length) { ?>
                <td class="column-size-7-5 light-grey-dotted-border-left">
                    <div class="icon-font icon-dot circle-outer primary-colour">
                        <div class="icon-font icon-circle circle-inner"></div>
                    </div>
                </td>
                <?php } else { ?>
                <td class="column-size-7-5 no-padding-left vertical-align-top">
                    <table class="margin-top-minus-1">
                        <tr>
                            <td class="light-grey-dotted-border-left">&nbsp;
                                <div class="icon-font icon-dot circle-outer primary-colour">
                                    <div class="icon-font icon-circle circle-inner"></div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
                <?php } ?>
                <td class="small-size padding-left-1 column-size-24-5"><?= '<span class="darkest-grey">' . $experience->position . ' – <br />' . $experience->company . '</span>' .
                    '<br />' .
                    (
                        ($key === 0)
                        ? '<span class="padding-top-1">' .
                        date_format(date_create($experience->startDate), 'M Y') . ' – ' .
                        ((empty($experience->endDate)) ? '<em>Present</em>' : date_format(date_create($experience->endDate), 'M Y')) .
                        '</span>' .
                        ((empty($experience->endDate)) ? '<br />(' . get_relative_time(date_format(date_create($experience->startDate . ' 00:00:00'), 'U'), true) . ')' : '')
                        : date_format(date_create($experience->startDate), 'M Y') . ' – ' .
                        date_format(date_create($experience->endDate), 'M Y')
                    );
                ?></td>
                <td class="small-size padding-left-2 <?= ($key % 2 === 0) ? 'padding-top-2' : 'padding-top-1-5' ?>">
                    <?= complex_wordwrap(97, '<span class="icon-font icon-dot medium-size dark-grey"></span>', '<span class="vertical-align-top padding-left-14">%s</span>', $experience->summary) ?>
                </td>
            </tr>
            <?php if ($key < $length) { ?>
            <tr class="spacer padding-bottom-5">
                <td class="column-size-2-5"></td>
                <td class="column-size-7-5 light-grey-dotted-border-left"></td>
                <td></td>
                <td></td>
            </tr>
            <?php } ?>
            <?php } ?>
        </tbody>
    </table>
    <!-- End of Section 2 - Experience -->
    <table class="sectional-spacer">
        <tbody>
            <tr>
                <td class="column-size-9"></td>
                <td class="primary-colour-border-bottom-2 padding-top-4"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <!-- Start of Section 3 - Expertise -->
    <table class="no-wrap">
        <tbody>
            <tr class="vertical-align-top">
                <td class="x-large-size column-size-10 padding-left-8 padding-top-5 primary-colour"><span class="icon-font icon-brain"></span></td>
                <td class="large-size all-caps primary-colour column-size-25-2">3. Expertise</td>
                <?= simple_wordwrap('<td class="padding-top-5 small-size">%s</td>', implode(', ', $yaml->skills[0]->keywords), COLUMNS_EXPERTISE) ?>
            </tr>
        </tbody>
    </table>
    <!-- End of Section 3 - Expertise -->
    <table class="sectional-spacer">
        <tbody>
            <tr>
                <td class="column-size-9"></td>
                <td class="primary-colour-border-bottom-2 padding-top-4"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <!-- Start of Section 4 - About Me -->
    <table class="no-wrap">
        <tbody>
            <tr class="primary-colour vertical-align-bottom">
                <td class="x-large-size column-size-10 padding-left-8"><span class="icon-font icon-person"></span></td>
                <td class="large-size all-caps padding-left-1">5. About Me</td>
            </tr>
        </tbody>
    </table>
    <table class="no-wrap">
        <tbody>
            <tr>
                <td class="column-size-10"></td>
                <td class="padding-left-1 small-size">
                <?= complex_wordwrap(136, '', '', $yaml->basics->summary, false) ?>
                </td>
            </tr>
        </tbody>
    </table>
    <!-- End of Section 4 - About Me -->
    <table class="sectional-spacer">
        <tbody>
            <tr>
                <td class="column-size-9"></td>
                <td class="primary-colour-border-bottom-2 padding-top-4"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <!-- Start of Section 5 - Professional Development -->
    <table class="no-wrap">
        <tbody>
            <tr class="primary-colour vertical-align-bottom">
                <td class="x-large-size column-size-10 padding-left-8"><span class="icon-font icon-star"></span></td>
                <td class="large-size all-caps">4. Professional Development</td>
            </tr>
        </tbody>
    </table>
    <table class="no-wrap small-size">
        <tbody>
            <tr class="spacer">
                <td class="padding-top-5"></td>
            </tr>
            <?php
                $course_count = 0;
                $courses      = $yaml->training;
                usort($courses, 'date_compare_courses');

                $initial_row = '<tr><td class="column-size-11-5"></td><td class="dark-grey light-grey-border lightest-grey-background column-size-80 padding-left-3">%s</td><td class="light-grey-border lightest-grey-background right-align-text column-size-10 padding-right-3">%s</td><td class="light-grey-border lightest-grey-background right-align-text column-size-10 padding-right-4">%s</td><td class="column-size-1-5"></td></tr>';
                $odd_row     = '<tr><td></td><td class="dark-grey light-grey-border lightest-grey-background padding-left-3">%s</td><td class="light-grey-border lightest-grey-background right-align-text padding-right-3">%s</td><td class="light-grey-border lightest-grey-background right-align-text padding-right-4">%s</td><td></td></tr>';
                $even_row    = '<tr><td></td><td class="dark-grey light-grey-border padding-left-3">%s</td><td class="light-grey-border right-align-text padding-right-3">%s</td><td class="light-grey-border right-align-text padding-right-4">%s</td><td></td></tr>';
                $all_rows    = '';

                foreach ($courses as $course) {
                    if (!isset($course->hide)) {
                        $course_count++;

                        if ($course_count === 1) {
                            $all_rows = sprintf($initial_row, $course->title, $course->date, $course->duration);
                        } else if ($course_count % 2 === 0) {
                            $all_rows .= sprintf($even_row, $course->title, $course->date, $course->duration);
                        } else {
                            $all_rows .= sprintf($odd_row, $course->title, $course->date, $course->duration);
                        }

                        if ($course_count === ROWS_PROFESSIONAL_DEVELOPMENT) {
                            break;
                        }
                    }
                }

                echo $all_rows;
            ?>
        </tbody>
    </table>
    <table class="no-wrap spacer">
        <tbody>
            <tr>
                <td class="padding-bottom-4"></td>
            </tr>
        </tbody>
    </table>
    <!-- End of Section 5 - Professional Development -->
    <table class="sectional-spacer">
        <tbody>
            <tr>
                <td class="column-size-9"></td>
                <td class="primary-colour-border-bottom-2 padding-top-4"></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <!-- Start of Section 6 - Referees -->
    <table class="no-wrap">
        <tbody>
            <tr>
                <td class="x-large-size column-size-10 padding-left-8 primary-colour vertical-align-bottom"><span class="icon-font icon-people"></span></td>
                <td class="large-size all-caps primary-colour vertical-align-bottom column-size-25-2">6. Referees</td>
                <td class="vertical-align-top padding-top-7 small-size">Available on request</td>
            </tr>
        </tbody>
    </table>
    <!-- End of Section 6 - Referees -->
    <script type="text/php">
        if (isset($pdf)) {
            if ($PAGE_COUNT > 1) {
                $y        = $pdf->get_height() - 34;
                $pageText = "{PAGE_NUM} of {PAGE_COUNT}";
                $font     = $fontMetrics->get_font('secca');
                $size     = 8;
                $x        = $pdf->get_width() - $fontMetrics->get_text_width('0 of 0', $font, $size) - 31;

                $pdf->page_text($x, $y, $pageText, $font, $size, [.3, .3, .3]); // rgb(76, 76, 76) Dark Grey
            }
        }
    </script>
</body>
</html>
<?php
    if (!PDF_MODE) {
        error_reporting(0);
        echo '<style>
            body {
                font-size: 130% !important;
                min-width: 875px;
            }

            .icon-circle { display: none }
            .light-grey-dotted-border-left { border: 0 }
        </style>';
        viewHTML();
    }