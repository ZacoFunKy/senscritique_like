<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerA3xkv31\App_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerA3xkv31/App_KernelDevDebugContainer.php') {
    touch(__DIR__.'/ContainerA3xkv31.legacy');

    return;
}

if (!\class_exists(App_KernelDevDebugContainer::class, false)) {
    \class_alias(\ContainerA3xkv31\App_KernelDevDebugContainer::class, App_KernelDevDebugContainer::class, false);
}

return new \ContainerA3xkv31\App_KernelDevDebugContainer([
    'container.build_hash' => 'A3xkv31',
    'container.build_id' => '8ea948eb',
    'container.build_time' => 1672922127,
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerA3xkv31');
