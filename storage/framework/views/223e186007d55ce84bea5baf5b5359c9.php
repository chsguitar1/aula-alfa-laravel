<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo e($title); ?></title>
</head>
<body>
    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <h1> Nome: <?php echo e($user->name); ?></h1>
    <p><a href="<?php echo e(url('/admin/user/' . $user->id)); ?>">Ver detalhes</a></p>

    <?php if($user->id === 10): ?>
        <p>Usuario com id 10</p>
    <?php else: ?>
        <p>Usuario com id  <> 10</p>
    <?php endif; ?>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php echo e($users->links()); ?>


</body>
</html>
<?php /**PATH C:\faculdade\4S\aula-alfa-laravel-main\resources\views/user/index.blade.php ENDPATH**/ ?>