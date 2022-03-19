<?php

namespace Jmerilainen\WpDemoCrm\Console\Commands;

use Jmerilainen\WpDemoCrm\Crm\Factory as ApiFactory;
use Jmerilainen\WpDemoCrm\Models\Account;
use Jmerilainen\WpDemoCrm\Repositories\AccountRepository;
use Jmerilainen\WpDemoCrm\Transformers\AccountToPost;

class AccountsCommand extends Command
{
    protected $command = 'app accounts';

    public function list()
    {
        $this->debug('Call API.');

        $accounts = $this->fetchAccounts();

        foreach($accounts as $account) {
            $this->line(
                json_encode($account, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . ','
            );
        }

        $this->debug('Done.');
    }

    public function update()
    {
        $this->line('Get accounts from API');

        $accounts = $this->fetchAccounts();

        $inserted = [];

        $this->line('Update or create accounts');

        $count = count($accounts);
        $progress = $this->progress('Processing', $count);

        foreach($accounts as $account) {
            $data = (new AccountToPost)->transform($account);

            $this->debug(
                json_encode($account, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            );

            if (! $data['active']) {
                $progress->tick();
                continue;
            }

            try {
                $inserted[] = Account::updateOrCreate(
                    ['title' => $data['name']],
                    $data
                );
            } catch (\Exception $e) {
                $this->line(
                    sprintf('Error for Account ID "%s" with message "%s"', $account->id, $e->getMessage())
                );
            }

            $progress->tick();
        }

        $progress->finish();

        $ids = array_map(function($post) {
            return $post->ID;
        }, $inserted);

        $this->line('Inserted ID\'s ' . join(', ', $ids));

        $this->line('Maybe purge non found accounts');

        $remove = (new AccountRepository)->purge($ids);

        if ($remove) {
            $this->line('Removed ID\'s ' . join(', ', $remove));
        } else {
            $this->line('Nothing to purge');
        }

    }

    protected function fetchAccounts()
    {
        $client = ApiFactory::makeWithToken();

        return $client->accounts()->all([
            'page[size]' => 1000,
            'fields[accounts]' => join(',', [
                'id',
                'name',
                'type',
                'email',
                'website',
                'city',
                'zip_code',
                'status',
            ])
        ]);
    }
}
