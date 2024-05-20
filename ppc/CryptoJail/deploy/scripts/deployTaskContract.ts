import { toNano } from '@ton/core';
import { TaskContract } from '../wrappers/TaskContract';
import { NetworkProvider } from '@ton/blueprint';

export async function run(provider: NetworkProvider) {
    const taskContract = provider.open(await TaskContract.fromInit(BigInt(Math.floor(Math.random() * 10000))));

    await taskContract.send(
        provider.sender(),
        {
            value: toNano('0.015'),
        },
        {
            $$type: 'Deploy',
            queryId: 0n,
        }
    );

    await provider.waitForDeploy(taskContract.address);

    console.log('ID', await taskContract.getId());
}
