<?php

declare(strict_types=1);

namespace App\Enums;

use Carbon\CarbonInterface;

/**
 * Сезон (время года), к которому относится сущность (например, аниме-тайтл).
 *
 * ЗАЧЕМ ЭТОТ ENUM:
 * Многие сайты об аниме группируют тайтлы по "сезонам выхода" —
 * зима/весна/лето/осень конкретного года. Этот enum:
 *   1) вычисляет сезон по дате (fromDate/fromMonth);
 *   2) даёт человекочитаемые формы этого сезона на нужном языке
 *      и в нужном падеже — через методы adjective()/noun()/adverb()
 *      и т.д., которые под капотом обращаются к языковым файлам
 *      lang/{locale}/seasons.php.
 *
 * ПОЧЕМУ ПЕРЕВОДЫ ВЫНЕСЕНЫ В lang/, А НЕ ЗАШИТЫ В enum:
 *   - Laravel сам решает, какой файл подгрузить, в зависимости от
 *     текущей локали приложения (App::getLocale()) — не нужно
 *     писать никакой ветвящейся логики внутри enum;
 *   - переводчики/контент-менеджеры могут править тексты в lang/,
 *     не трогая PHP-код и не требуя деплоя новой версии enum;
 *   - добавление нового языка — это просто новый файл lang/{locale}/seasons.php
 *     с такой же структурой ключей, без изменений в этом классе.
 *
 * @see resource_path('lang/ru/seasons.php') Русские переводы и склонения
 */
enum EntrySeason: string
{
    /**
     * Сезон не определён / неизвестен.
     * Используется как безопасное значение по умолчанию, если дату
     * определить не удалось (например, поле в БД пустое).
     */
    case UNKNOWN = 'unknown';

    /** Зима: январь, февраль, март (месяцы 1–3). */
    case WINTER = 'winter';

    /** Весна: апрель, май, июнь (месяцы 4–6). */
    case SPRING = 'spring';

    /** Лето: июль, август, сентябрь (месяцы 7–9). */
    case SUMMER = 'summer';

    /** Осень: октябрь, ноябрь, декабрь (месяцы 10–12). */
    case FALL = 'fall';

    /**
     * Определить сезон по номеру календарного месяца.
     *
     * Логика построена на границах кварталов:
     *   1–3   → WINTER (зима)
     *   4–6   → SPRING (весна)
     *   7–9   → SUMMER (лето)
     *   10–12 → FALL   (осень, ветка default — покрывает всё, что осталось)
     *
     * Метод не валидирует диапазон входного числа (1–12) — предполагается,
     * что $month всегда приходит из Carbon (->month), где он гарантированно
     * корректен. Если передать, например, 13, сработает ветка default (FALL).
     *
     * @param  int $month Номер месяца, 1 (январь) — 12 (декабрь)
     * @return self Соответствующий сезон
     *
     * @example
     *  EntrySeason::fromMonth(1)  === EntrySeason::WINTER
     *  EntrySeason::fromMonth(6)  === EntrySeason::SPRING
     *  EntrySeason::fromMonth(12) === EntrySeason::FALL
     */
    public static function fromMonth(int $month): self
    {
        return match (true) {
            $month <= 3 => self::WINTER,
            $month <= 6 => self::SPRING,
            $month <= 9 => self::SUMMER,
            default     => self::FALL,
        };
    }

    /**
     * Определить сезон по дате (объекту Carbon).
     *
     * Тонкая обёртка над fromMonth(): просто достаёт номер месяца
     * из даты и передаёт его дальше. Удобна, когда под рукой
     * уже есть Carbon-дата, а не голое число месяца.
     *
     * @param  CarbonInterface $date Любая дата, реализующая CarbonInterface
     *                                (Carbon, CarbonImmutable и т.п.)
     * @return self Сезон, соответствующий месяцу переданной даты
     *
     * @example
     *  EntrySeason::fromDate(now()) // сезон текущего момента
     *  EntrySeason::fromDate(Carbon::parse('2024-07-15')) === EntrySeason::SUMMER
     */
    public static function fromDate(CarbonInterface $date): self
    {
        return self::fromMonth($date->month);
    }

    /**
     * Прилагательная форма названия сезона в указанном падеже.
     *
     * Используется, когда сезон ОПИСЫВАЕТ другое существительное,
     * например "сезон": "зимний сезон", "к зимнему сезону".
     *
     * Строка берётся из языкового файла seasons.php текущей локали
     * по пути seasons.{значение_enum}.adjective.{падеж}, например
     * seasons.winter.adjective.genitive.
     *
     * @param  string $case Один из падежей:
     *                       'nominative'    — именительный (какой?)
     *                       'genitive'      — родительный (какого?)
     *                       'dative'        — дательный (какому?)
     *                       'accusative'    — винительный (какой?)
     *                       'instrumental'  — творительный (каким?)
     *                       'prepositional' — предложный (о каком?)
     * @return string Переведённое и склонённое прилагательное
     *
     * @example
     *  EntrySeason::WINTER->adjective()              // "зимний"
     *  EntrySeason::WINTER->adjective('genitive')    // "зимнего"
     *  EntrySeason::FALL->adjective('prepositional') // "осеннем"
     */
    public function adjective(string $case = 'nominative'): string
    {
        return __("seasons.{$this->value}.adjective.{$case}");
    }

    /**
     * Форма существительного — названия самого сезона — в указанном падеже.
     *
     * Используется, когда сезон выступает САМ ПО СЕБЕ как существительное,
     * без слова "сезон": "пришла зима", "жду весну", "листья опали осенью".
     *
     * Строка берётся из языкового файла seasons.php текущей локали
     * по пути seasons.{значение_enum}.noun.{падеж}, например
     * seasons.winter.noun.genitive.
     *
     * @param  string $case Падеж — те же значения, что и в adjective()
     * @return string Переведённое и склонённое существительное
     *
     * @example
     *  EntrySeason::WINTER->noun()                // "зима"
     *  EntrySeason::WINTER->noun('genitive')      // "зимы"
     *  EntrySeason::WINTER->noun('instrumental')  // "зимой"
     */
    public function noun(string $case = 'nominative'): string
    {
        return __("seasons.{$this->value}.noun.{$case}");
    }

    /**
     * Наречная форма сезона: "зимой", "весной", "летом", "осенью".
     *
     * Грамматически это творительный падеж существительного (noun),
     * но вынесен отдельным именованным методом для читаемости кода
     * в местах вызова — так вызывающий код не обязан "знать",
     * что наречие технически совпадает с творительным падежом.
     *
     * @return string Наречие, отвечающее на вопрос "когда?"
     *
     * @example
     *  EntrySeason::SUMMER->adverb() // "летом" — "мы отдыхали летом"
     */
    public function adverb(): string
    {
        return $this->noun('instrumental');
    }

    /**
     * Существительное в предложном падеже с автоматически подобранным предлогом.
     *
     * В русском языке предлог "о" перед словом, начинающимся с гласной,
     * по фонетическим причинам меняется на "об": "о зиме", но "об осени".
     * Метод сам проверяет первую букву формы предложного падежа
     * (через startsWithVowel()) и подставляет нужный предлог,
     * так что вызывающему коду не нужно об этом думать.
     *
     * @return string Готовая конструкция "предлог + существительное",
     *                 например "о зиме" или "об осени"
     *
     * @example
     *  EntrySeason::WINTER->prepositionalWithPreposition() // "о зиме"
     *  EntrySeason::FALL->prepositionalWithPreposition()   // "об осени"
     */
    public function prepositionalWithPreposition(): string
    {
        $noun = $this->noun('prepositional');
        $preposition = $this->startsWithVowel($noun) ? 'об' : 'о';

        return "{$preposition} {$noun}";
    }

    /**
     * Проверить, начинается ли слово с русской гласной буквы.
     *
     * Вспомогательный (приватный) метод только для внутренней логики
     * prepositionalWithPreposition() — выбора между предлогами "о"/"об".
     * Сравнение регистронезависимое: слово сначала приводится к нижнему
     * регистру через mb_strtolower (важно для многобайтовой кириллицы,
     * обычный strtolower её испортит).
     *
     * @param  string $word Слово, у которого проверяется первая буква
     *                       (регистр не важен: "Осень" и "осень" — равнозначны)
     * @return bool true — если первая буква одна из: а е ё и о у ы э ю я
     */
    private function startsWithVowel(string $word): bool
    {
        $vowels = ['а', 'е', 'ё', 'и', 'о', 'у', 'ы', 'э', 'ю', 'я'];
        $firstLetter = mb_strtolower(mb_substr($word, 0, 1));

        return in_array($firstLetter, $vowels, true);
    }

    /**
     * Готовая фраза-заголовок вида "Аниме зимнего сезона".
     *
     * Собирает строку по шаблону из seasons.anime_season_label,
     * подставляя вместо плейсхолдера :season форму прилагательного
     * в родительном падеже (adjective('genitive')) — именно эта форма
     * грамматически верна для конструкции "Аниме <какого?> сезона".
     *
     * Вынесение шаблона в языковой файл позволяет менять формулировку
     * заголовка (например, порядок слов) централизованно, не трогая
     * этот метод и не требуя изменений в местах использования.
     *
     * @return string Готовая для вывода фраза,
     *                 например "Аниме зимнего сезона"
     *
     * @example
     *  EntrySeason::WINTER->animeSeasonLabel() // "Аниме зимнего сезона"
     *  EntrySeason::FALL->animeSeasonLabel()   // "Аниме осеннего сезона"
     */
    public function animeSeasonLabel(): string
    {
        return __('seasons.anime_season_label', [
            'season' => $this->adjective('genitive'),
        ]);
    }
}
