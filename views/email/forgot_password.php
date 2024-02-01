Hello <?= $user->firstname ?> <?= $user->lastname ?>,

You or someone else has requested to reset your account password for Yooply.  To reset your password, click on the following link:
<?= base_url() ?>reset/<?= $user->userid ?>/<?= $reset->hashkey ?>


If you didn't request to change your password, please let us know by contacting support:
<?= base_url() ?>contact

Sincerely,
The Yooply Team