import { toNano } from '@ton/core';
import { ContractSystem } from '@tact-lang/emulator';
import { TaskContract } from '../build/TaskContract/tact_TaskContract';

describe('contract', () => {
    it('should deploy correctly', async () => {
        // Create ContractSystem and deploy contract
        let system = await ContractSystem.create();
        let owner = system.treasure('owner');
        let nonOwner = system.treasure('non-owner');
        let contract = system.open(await TaskContract.fromInit(BigInt(Math.floor(Math.random() * 10000))));
        let logger = system.log(contract.address);
        system.name(contract.address, 'main');
        let track = system.track(contract);
        await contract.send(owner, { value: toNano('0.1') }, { $$type: 'Deploy', queryId: 0n });
        await system.run();
        expect(track.collect()).toMatchInlineSnapshot(`
            [
              {
                "$seq": 0,
                "events": [
                  {
                    "$type": "deploy",
                  },
                  {
                    "$type": "received",
                    "message": {
                      "body": {
                        "type": "known",
                        "value": {
                          "$$type": "Deploy",
                          "queryId": 0n,
                        },
                      },
                      "bounce": true,
                      "from": "@treasure(owner)",
                      "to": "@main",
                      "type": "internal",
                      "value": "0.1",
                    },
                  },
                  {
                    "$type": "processed",
                    "gasUsed": 8305n,
                  },
                  {
                    "$type": "sent",
                    "messages": [
                      {
                        "body": {
                          "type": "known",
                          "value": {
                            "$$type": "DeployOk",
                            "queryId": 0n,
                          },
                        },
                        "bounce": false,
                        "from": "@main",
                        "to": "@treasure(owner)",
                        "type": "internal",
                        "value": "0.090499",
                      },
                    ],
                  },
                ],
              },
            ]
        `);

        // Check counter
        expect(await contract.getSeqno()).toEqual(0n);
        // Increment counter
        let res = await contract.send(
            owner,
            { value: toNano('0.2') },
            { $$type: 'CheckKey', guessedKey: 500n, seqno: 0n }
        );
        await system.run();
        const trackLog = track.collect();
        // console.log(trackLog);
        console.log(logger.collect());
        expect(trackLog).toMatchInlineSnapshot(`
            [
              {
                "$seq": 1,
                "events": [
                  {
                    "$type": "received",
                    "message": {
                      "body": {
                        "type": "known",
                        "value": {
                          "$$type": "CheckKey",
                          "guessedKey": 500,
                          "seqno": 0,
                        },
                      },
                      "bounce": true,
                      "from": "@treasure(owner)",
                      "to": "@main",
                      "type": "internal",
                      "value": "0.2",
                    },
                  },
                  {
                    "$type": "processed",
                    "gasUsed": 7703n,
                  },
                  {
                    "$type": "sent",
                    "messages": [
                      {
                        "body": {
                          "text": "Wrong key",
                          "type": "text",
                        },
                        "bounce": true,
                        "from": "@main",
                        "to": "@treasure(owner)",
                        "type": "internal",
                        "value": "0.191093",
                      },
                    ],
                  },
                ],
              },
            ]
        `);

        // Check counter
        // expect(await contract.getSeqno()).toEqual(1n);
    });
});
