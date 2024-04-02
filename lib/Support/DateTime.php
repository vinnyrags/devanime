<?php

namespace DevAnime\Support;

use DateTimeInterface, DateTimeZone;

/**
 * class DateTime
 * @package DevAnime\Support
 */
class DateTime extends \DateTime implements \JsonSerializable
{
    public const DEFAULT_FORMAT = DATE_ATOM;

    private bool $isDST;
    private string $defaultFormat;

    public function __construct($time = 'now', ?string $defaultFormat = null, ?DateTimeZone $timezone = null)
    {
        $this->isDST = $this->setDST($time);
        $this->defaultFormat = $defaultFormat ?? self::DEFAULT_FORMAT;
        parent::__construct($time, $this->getDefaultTimezone($timezone));
    }

    public static function createFromTimestamp(int $timestamp, ?string $defaultFormat = null, ?DateTimeZone $timezone = null): self
    {
        $datetime = new static("@$timestamp", $defaultFormat, new DateTimeZone('UTC'));
        $datetime->setTimezone($datetime->getDefaultTimezone($timezone));
        return $datetime;
    }

    public function isBetween(DateTimeInterface $dateBefore, DateTimeInterface $dateAfter): bool
    {
        return $this->isAfter($dateBefore) && $this->isBefore($dateAfter);
    }

    public function timestampDiff(DateTimeInterface $date): int
    {
        return $this->getTimestamp() - $date->getTimestamp();
    }

    public function isBefore(DateTimeInterface $date): bool
    {
        return $this->timestampDiff($date) < 0;
    }

    public function isAfter(DateTimeInterface $date): bool
    {
        return $this->timestampDiff($date) > 0;
    }

    public function isSameDayAs(DateTimeInterface $date): bool
    {
        return $this->format('Y-m-d') === $date->format('Y-m-d');
    }

    public function isSameMonthAs(DateTimeInterface $date): bool
    {
        return $this->format('Y-m') === $date->format('Y-m');
    }

    public function isSameYearAs(DateTimeInterface $date): bool
    {
        return $this->format('Y') === $date->format('Y');
    }

    public function isPast(): bool
    {
        return $this->getTimestamp() < time();
    }

    public function isFuture(): bool
    {
        return $this->getTimestamp() > time();
    }

    public function isDaylightSavings(): bool
    {
        return $this->isDST;
    }

    public function __toString(): string
    {
        return $this->format($this->defaultFormat);
    }

    public function jsonSerialize(): string
    {
        return $this->format(DATE_RFC2822);
    }

    private function setDST($time): bool
    {
        $localtimeAssoc = localtime(strtotime($time), true);
        $isDST = !empty($localtimeAssoc['is_dst']) || !empty($localtimeAssoc['tm_isdst']);
        return (bool)$isDST !== -1;
    }

    private function getDefaultTimezone(?DateTimeZone $timezone = null): DateTimeZone
    {
        return $timezone ?? new DateTimeZone($this->getDefaultTimezoneName());
    }

    private function getDefaultTimezoneName(): string
    {
        if (!function_exists('get_option')) {
            return date_default_timezone_get();
        }
        if ($timezoneString = get_option('timezone_string')) {
            return $timezoneString;
        }
        return timezone_name_from_abbr('', get_option('gmt_offset', 0) * 3600, $this->isDST);
    }
}
