<?php
namespace Grav\Plugin;

use Grav\Common\Config\Config;
use Grav\Common\Grav;
use \Grav\Common\Plugin;

/**
 * Class OpeningHoursPlugin
 * @package Grav\Plugin
 */
class OpeningHoursPlugin extends Plugin {
    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     *
     */

    private $template_post_html = 'partials/opening-hours.html.twig';

    // Initialize configuration.
    public function onPluginsInitialized() {
        $this->enable(['onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
            'onTwigInitialized' => ['onTwigInitialized', 0]]);
    }

    // Add Twig Extensions.
    public function onTwigInitialized() {


        $this->grav['twig']->twig->addFunction(new \Twig_SimpleFunction('opening_hours', [$this, 'getHours']));

        if  ($this->settings()['values']['enableDaysList'] == true) {
            $this->grav['assets']->addJs('plugin://opening-hours/js/opening-hours.js', ['group' => 'bottom']);
        }
        if ($this->settings()['values']['disableCSS'] == true || $this->settings()['values']['disableCSS'] == null) {
            $this->grav['assets']->add('plugin://opening-hours/css/opening-hours.css');
        }
    }


    //Add current directory to twig lookup paths.
    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }


    public function getLangTime() {
        $getSystemLang = $this->config->get('site.default_lang');
        setlocale(LC_TIME, $getSystemLang);

        //set Timezone, Day & get Current Time
        if($getSystemLang == 'en' || $getSystemLang === 'en_US') {
            $currentTime = date('h:i A', time());
        } else {
            $currentTime = date('H:i', time());
        }
        $currentDay = strtolower(strftime("%A"));

        return array(
            'day' => $currentDay,
            'time' => $currentTime,
            'systemLang' => $getSystemLang
        );
    }


    public function settings() {

        $enableDaysList = $this->config->get('plugins.opening-hours.settings.daysList');
        $disableCSS = $this->config->get('plugins.opening-hours.settings.enableCSS');

        $settings['values'] = [
            'enableDaysList' => $enableDaysList,
            'disableCSS' => $disableCSS
        ];
        return $settings;
    }


    public function getHours() {

        $currentDay = $this->getLangTime()['day'];
        $currentTime = $this->getLangTime()['time'];
        $getSystemLang = $this->getLangTime()['systemLang'];

        $days = $this->getAllDays()['daysArray'];

        //Get current day, hours and translate it

        foreach ($days as $day) {
            $dayShortcode = substr($day, 0, 3);

            if ($currentDay === $day) {
                $dayTranslation = $this->grav['language']->translate(['PLUGIN_OPENING_HOURS.DAYS.' . strtoupper($day)]);

                if($getSystemLang == 'en' || $getSystemLang === 'en_US') {

                    $timeStartToString = strtotime($this->config->get('plugins.opening-hours.' . $dayShortcode . '.start'));
                    $timeEndToString = strtotime($this->config->get('plugins.opening-hours.' . $dayShortcode . '.end'));

                    $timeStart = date('h:i A', $timeStartToString);
                    $timeEnd = date('h:i A', $timeEndToString);

                    $lunchStartToTime = strtotime($this->config->get('plugins.opening-hours.' . $dayShortcode . '.lunchStart'));
                    $lunchEndToTime = strtotime($this->config->get('plugins.opening-hours.' . $dayShortcode . '.lunchEnd'));

                    $lunchStart = date('h:i A', $lunchStartToTime);
                    $lunchEnd = date('h:i A', $lunchEndToTime);

                } else {

                    $timeStartToString = strtotime($this->config->get('plugins.opening-hours.' . $dayShortcode . '.start'));
                    $timeEndToString = strtotime($this->config->get('plugins.opening-hours.' . $dayShortcode . '.end'));

                    $lunchStartToTime = strtotime($this->config->get('plugins.opening-hours.' . $dayShortcode . '.lunchStart'));
                    $lunchEndToTime = strtotime($this->config->get('plugins.opening-hours.' . $dayShortcode . '.lunchEnd'));

                    if ($timeStartToString && $timeEndToString) {
                        $timeStart = date('H:i', $timeStartToString);
                        $timeEnd = date('H:i', $timeEndToString);

                        $lunchStart = date('H:i', $lunchStartToTime);
                        $lunchEnd = date('H:i', $lunchEndToTime);

                    } else {
                        $timeStart = false;
                        $timeEnd = false;

                        $lunchStart = false;
                        $lunchEnd = false;
                    }
                }

                // Set opened true or false
                if ($currentTime > $timeEnd || $currentTime < $timeStart) {
                    $opened =  false;
                } else {
                    $opened =  true;
                }


                // Check Minutes to opening or closing
                $minutesToStart = (round(abs($timeStartToString - strtotime($currentTime)) / 60,2));
                $minutesToEnd = (round(abs(strtotime($currentTime) - $timeEndToString) / 60,2));

                if ($minutesToStart <= 30 && $currentTime < $timeStart || $minutesToEnd <= 30 && $currentTime < $timeEnd) {
                    $toStart = $minutesToStart;
                    $toEnd = $minutesToEnd;
                    $minutesLeft = true;
                } else {
                    $toStart = false;
                    $toEnd = false;
                    $minutesLeft = false;
                }

                //Check Minutes to Lunchbreak
                $minutesToLunchStart = (round(abs($lunchStartToTime - strtotime($currentTime)) / 60,2));
                $minutesToLunchEnd = (round(abs( strtotime($currentTime) - $lunchEndToTime) / 60,2));

                if ($minutesToLunchStart <= 30 && $currentTime <= $lunchStart || $minutesToLunchEnd <= 30 && $currentTime < $lunchEnd && $currentTime > $lunchStart) {
                    $toLunchStart = $minutesToLunchStart;
                    $toLunchEnd = $minutesToLunchEnd;
                    $lunchMinutesLeft = true;
                } else {
                    $toLunchStart = false;
                    $lunchMinutesLeft = false;
                    $toLunchEnd = false;
                }


                // array for current day
                $values['values'][] = [
                    'day' => $dayTranslation,
                    'opened' => $opened,
                    'timeStart' => $timeStart,
                    'timeEnd' => $timeEnd,
                    'toStart' => $toStart,
                    'minutesLeft' => $minutesLeft,
                    'toEnd' => $toEnd,
                    'lunchStart' => $lunchStart,
                    'lunchEnd' => $lunchEnd,
                    'toLunchStart' => $toLunchStart,
                    'toLunchEnd' => $toLunchEnd,
                    'lunchMinutesLeft' => $lunchMinutesLeft
                ];
            }
        }

        $output = $this->grav['twig']->twig()->render($this->template_post_html,
            array(
                'data' => $values,
                'allDays' => $this->getAllDays()['allDays'],
                'settings' => $this->settings(),
        ));

        return $output;

    }


    public function getAllDays(){

        $days = [
            'mon' => $this->config->get('plugins.opening-hours.mon.hidden'),
            'tue' => $this->config->get('plugins.opening-hours.tue.hidden'),
            'wed' => $this->config->get('plugins.opening-hours.wed.hidden'),
            'thu' => $this->config->get('plugins.opening-hours.thu.hidden'),
            'fri' => $this->config->get('plugins.opening-hours.fri.hidden'),
            'sat' => $this->config->get('plugins.opening-hours.sat.hidden'),
            'sun' => $this->config->get('plugins.opening-hours.sun.hidden')
        ];

        foreach ($days as $day) {

            $dayShortcode = substr($day, 0, 3);

            $allDays['values'][] = [
                'days' => $this->grav['language']->translate(['PLUGIN_OPENING_HOURS.DAYS.' . strtoupper($day)]),
                'daysStart' => $this->config->get('plugins.opening-hours.' . $dayShortcode . '.start'),
                'daysEnd' => $this->config->get('plugins.opening-hours.' . $dayShortcode . '.end'),
                'lunchStart' => $this->config->get('plugins.opening-hours.' . $dayShortcode . '.lunchStart'),
                'lunchEnd' => $this->config->get('plugins.opening-hours.' . $dayShortcode . '.lunchEnd')
            ];

        }

        return array(
            'allDays' => $allDays,
            'daysArray' => $days
        );
    }

}


