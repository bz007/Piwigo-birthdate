<?php
defined('BIRTHDATE_PATH') or die('Hacking attempt!');

/**
 * Spelling according to Russian style which spells different for 1, 2..4 and 5..9
 * returns format string like '%d year234'
 */
function birthdate_spell($n, $periods='year')
{
  if ($n > 20) {
    if     ($n % 10 > 4)  $periods .= '56789';
    elseif ($n % 10 > 1)  $periods .= '234';
    elseif ($n % 10 == 1) $periods .= '1';
    else                  $periods .= '56789';
  }
  elseif ($n > 4)  $periods .= '56789';
  elseif ($n > 1)  $periods .= '234';
  elseif ($n == 1) $periods .= '1';
  else             $periods .= '56789';

  return '$d '.$periods;
}

function birthdate_compute_age($birthdate, $date_ref=null)
{
  $birthdate_unixtime = strtotime($birthdate);

  if (!isset($date_ref))
  {
    $date_ref_unixtime = time();
  }
  else
  {
    $date_ref_unixtime = strtotime($date_ref);
  }

  $nb_seconds = $date_ref_unixtime - $birthdate_unixtime;

  if ($nb_seconds < 0)
  {
    return null;
  }

  $nb_years = floor($nb_seconds / (60*60*24*365.25));

  // older 3 years, just yeyars only
  if ($nb_years >= 3)
  {
    return sprintf(l10n(birthdate_spell($nb_years, 'year')), $nb_years);
  }

  $nb_months = floor($nb_seconds / (60*60*24*30.4)) - $nb_years*12; // average 30.4 days each 

  // between 1 and 3 years, put years and monthes
  if ($nb_years >= 1)
  {
    return sprintf(l10n(birthdate_spell($nb_years, 'year')), $nb_years) .
           (($nb_months > 0) ? ' '.sprintf(l10n(birthdate_spell($nb_months, 'month')), $nb_months) : '');
  }

  // between 3 an 12 months, just months only
  if ($nb_months >= 3)
  {
    return sprintf(l10n(birthdate_spell($nb_months, 'month')), $nb_months);
  }

  $nb_days = $nb_seconds / (60*60*24);

  // between 1 and 3 months, put months and days
  if ($nb_months >= 1)
  {
    return sprintf(l10n(birthdate_spell($nb_months, 'month')), $nb_months) .
           (($nb_days > 0) ? sprintf(l10n(birthdate_spell($nb_days, 'day')), $nb_days) : '');
  }

  // just days
  if ($nb_days >= 2)
  {
    return sprintf(l10n(birthdate_spell($nb_days, 'day')), $nb_days);
  }

  $nb_hours = $nb_seconds / (60*60);
  if ($nb_hours >= 2)
  {
    return sprintf(l10n('%d hours'), $nb_hours);
  }
  
  $nb_minutes = $nb_seconds / 60;
  if ($nb_minutes >= 2)
  {
    return sprintf(l10n('%d minutes'), $nb_minutes);
  }

  return sprintf(l10n('%d seconds'), $nb_seconds);
}
?>