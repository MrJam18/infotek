<?php

declare(strict_types=1);

namespace common\models\queries;

use common\models\Author;
use yii\db\ActiveQuery;

/**
 * @see Author
 */
class AuthorQuery extends ActiveQuery
{
    /**
     * Top authors by the number of books published in the given year (single query).
     *
     * @return Author[]
     */
    public function topByYear(int $year, int $limit = 10): array
    {
        return $this
            ->alias('a')
            ->select(['a.id', 'a.full_name', 'COUNT(*) AS books_count'])
            ->innerJoin('{{%book_author}} ba', 'ba.author_id = a.id')
            ->innerJoin('{{%book}} b', 'b.id = ba.book_id')
            ->andWhere(['b.year' => $year])
            ->groupBy(['a.id', 'a.full_name'])
            ->orderBy(['books_count' => SORT_DESC, 'a.id' => SORT_ASC])
            ->limit($limit)
            ->all();
    }
}
