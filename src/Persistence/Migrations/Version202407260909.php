<?php

namespace MrAndMrsSmith\IdempotentConsumerDoctrinePersistenceBundle\Persistence\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version202407260909 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
        create table idempotent_consumer_doctrine_persistence_messages_statuses
            (
                id                     int,
                message_status         varchar(255) not null,
                message_idempotent_key varchar(255) not null,
                message_name           varchar(255) not null
            );
            
            create index idcdp_msid_index
                on idempotent_consumer_doctrine_persistence_messages_statuses (id);
            
            create index idcdp_msmi_key_index
                on idempotent_consumer_doctrine_persistence_messages_statuses (message_idempotent_key);
            
            alter table idempotent_consumer_doctrine_persistence_messages_statuses
                add constraint idcdp_messages_statuses_pk
                    primary key (id);
            
            alter table idempotent_consumer_doctrine_persistence_messages_statuses
                add constraint iidcdp_messages_statuses_pk_2
                    unique (message_idempotent_key);
            
            alter table idempotent_consumer_doctrine_persistence_messages_statuses
                modify id int auto_increment;
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('drop table idempotent_consumer_doctrine_persistence_messages_statuses');
    }
}
