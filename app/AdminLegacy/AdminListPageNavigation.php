<?php
namespace App\AdminLegacy;

use App\Utils\URL;

class AdminListPageNavigation
{
    private $filteredItemsCount;
    private array $groupedCounts;
    public function __construct(
        public array $filters,
        private array $counts,
        public mixed $pageNum = null,
        private int $pageSize = 5,
        private string $countField = 'count',
        private URL|null $baseUrl = null,
        private \Closure|null $aggregator = null,
    ) {
        if ($this->pageSize) {
            $this->pageNum = max(1, min((int)($pageNum ?? $_GET['page'] ?? 1), $this->pageSize));
        }

        // load
        foreach ($this->filters as &$filter) {
            $filter = (object)$filter;
            $filter->value = match ($filter->type ?? 'string') {
                'int'   => $filter->value = (int)($_GET[$filter->param] ?? 0),
                'float' => $filter->value = (float)($_GET[$filter->param] ?? 0),
                default => $filter->value = ($_GET[$filter->param] ?? ''),
            };
        }

    }

    public function getWhereClauseAndOffset() {
        $WHERE = [];
        foreach ($this->filters as $filter) {
            if ($filter->value) {
                $WHERE[] = $filter->dbField.' = ?';
                $whereVals[] = $filter->value;
            }
        }

        $WHERE = $WHERE ? implode(" AND ", $WHERE) : '1';

        $offset = $this->pageSize * ($this->pageNum - 1);

        return (object)[
            'WHERE' => $WHERE,
            'whereVals' => $whereVals ?? [],
            'offset' => $offset,
            'pageSize' => $this->pageSize,
        ];
    }

    public function getGroupedCounts() {
        $root = [];
        $countFilters = count($this->filters);
        foreach ($this->counts as $countLine) {
            $lineItems = &$root;
            foreach (array_values($this->filters) as $level => $f) {
                $itemName = $countLine->{$f->param};
                $lineItems[$itemName] ??= (object)['count' => 0];
                foreach ($f->data ?? [] as $df) $lineItems[$itemName]->{$df} = $countLine->{$df};
                if ($agg = $this->aggregator) {
                    $agg($lineItems[$itemName], $countLine, $level);
                } else {
                    $lineItems[$itemName]->count += $countLine->{$this->countField};
                }
                if ($level < $countFilters-1) {
                    $lineItems[$itemName]->items ??= [];
                    $lineItems = &$lineItems[$itemName]->items;
                }
            }
        }

        return $root;
    }

    public function printPageLinks() {
        // get total number of items
        if (!isset($this->filteredItemsCount)) {
            $counts = $this->counts;
            // 1. apply filters
            foreach ($this->filters as $filter) {
                if ($filter->value) {
                    $counts = array_filter($counts, fn($c) =>
                        $c->{$filter->param} === $filter->value
                    );
                }
            }
            // 2. sum(count) from filtered items
            $this->filteredItemsCount = array_sum(array_map(
                fn($ct) => $ct->{$this->countField}, $counts));
        }

        $pageCount = ceil($this->filteredItemsCount / $this->pageSize);

        for ($i = 1; $i <= $pageCount; $i++) {
            $class = $i == $this->pageNum ? 'page current' : 'page';
            $url = URL::get()->setParam('page', $i);
            $links[] = "<a href='$url'><span class='$class'>$i</span></a>";
        }
        return implode(' ', $links ?? []);
    }

    public function printMenuTree(array $items = null, $lvl = null, URL|null $baseUrl = null) {

        $baseUrl ??= $this->baseUrl ?? URL::getRelative()->resetQuery();
        $items ??= $this->getGroupedCounts();
        $keys = $this->filters;
        $lvl ??= 0;
        $filter = $keys[$lvl];
        $defaultItemDisplay = fn($k, $item) => $k;

        foreach ($items as $k => $item)
        {
            $count = ($lvl && $item->count > 1) ? " <small>($item->count)</small>" : '';
            $selected = ($filter->value === $k ? 'active' : '');
            $url = $baseUrl->clone()->setParam($filter->param, $k);
            $itemLabel = ($filter->display ?? $defaultItemDisplay)($k, $item) . $count;
            ?>
            <li class='<?=$selected?>'>
                <a href="<?=$url?>" class='<?=$selected?>'><?=$itemLabel?></a>
            <?php if ($selected && isset($item->items)) { ?>
                <ul>
                <?php $this->printMenuTree($item->items, $lvl+1, $url); ?>
                </ul>
            <?php } ?>
            </li>
        <?php
        }
    }
}
