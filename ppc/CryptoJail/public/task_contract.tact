import "@stdlib/deploy";

message CheckKey {
    guessedKey: Int as uint32;
    seqno: Int as uint32;
}

message CheckKeyOk {
    seqno: Int as uint32;
    guesser: Address;
}

contract TaskContract with Deployable {
    owner: Address;
    id: Int as uint32;
    seqno: Int as uint32;
    key: Int as uint32;
    solvers: map<Int, Address>;

    init(id: Int) {
        self.owner = sender();
        self.id = id;
        self.key = random(0, 500);
        self.seqno = 0;
    }

    fun checkSolvers(addr: Address): Bool {
        let i: Int = 0;
        while (i < self.seqno) {
            if (self.solvers.get(i) == addr) {
                return true;
            }
            i = i + 1;
        }
        return false;
    }

    receive(msg: CheckKey) {
        if (msg.seqno != self.seqno) {
            self.reply("Sequence was completed, you need to take a new sequence".asComment());
            return;
        }
        else if (msg.guessedKey != self.key) {
            self.reply("Wrong key".asComment());
            return;
        }
        else if (self.checkSolvers(sender())) {
            self.reply("You have already completed the challenge".asComment());
            return;
        }
        else if (msg.seqno == self.seqno && msg.guessedKey == self.key) {
            send(SendParameters{
                to: self.owner, 
                value: 0,
                mode: SendRemainingValue | SendIgnoreErrors,
                body: sender().toString().asComment()    // need to manualy build @tact-lang/tact to compiler
            });
            self.solvers.set(self.seqno, sender());
            self.seqno = self.seqno + 1;
            self.key = random(0, 500);
            self.reply("You won! Wait your flag in encrypted message from contract owner.".asComment());
        }
        self.reply("Something went wrong".asComment());
    }

    get fun solvers(): map<Int, Address> {
        return self.solvers;
    }

    get fun solversStr(): String {
        let i: Int = 0;
        let sb: StringBuilder = beginString();
        sb.append("Addresses: ");
        while (i < self.seqno) {
            sb.append(i.toString());
            sb.append(" - ");
            let s: Address? = self.solvers.get(i);
            sb.append((s!!).toString());
            sb.append("\n");
            i = i + 1;
        }
        return sb.toString();
    }

    get fun seqno(): Int {
        return self.seqno;
    }

    get fun owner(): String {
        return self.owner.toString();
    }

    get fun id(): Int {
        return self.id;
    }
}
