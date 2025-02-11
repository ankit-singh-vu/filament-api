touch ssh_key && echo "$STAGING_VM_KEY" > ssh_key
chmod 0600 ssh_key;

touch code_ssh_key && echo "$CODE_SSH_KEY" > code_ssh_key
chmod 0600 code_ssh_key;

scp -i ssh_key bin/pipeline/staging_to_deploy/actual_deployment_to_staging_vm.sh $STAGING_VM_USER@$STAGING_VM_HOST:/home/ubuntu
scp -i ssh_key code_ssh_key $STAGING_VM_USER@$STAGING_VM_HOST:/home/ubuntu

 ssh -t -t -o 'StrictHostKeyChecking no' -i ssh_key $STAGING_VM_USER@$STAGING_VM_HOST << EOF
    mv actual_deployment_to_staging_vm.sh deploy_to_staging_vm.sh && \
    chmod +x deploy_to_staging_vm.sh && \
    ./deploy_to_staging_vm.sh $DOCKER_URL $DOCKER_USER $DOCKER_PASS $DATABASE_PASS $DATABASE_PASS_ROOT $STAGING_APP_URL && exit;
EOF
