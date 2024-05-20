from sqlalchemy import Column, Integer, String, TEXT, ForeignKey, DATETIME, BOOLEAN
from sqlalchemy.orm import DeclarativeBase, Mapped, relationship, mapped_column
from flask_sqlalchemy import SQLAlchemy
from sqlalchemy.ext.hybrid import hybrid_method
from datetime import datetime
from bcrypt import checkpw


class Base(DeclarativeBase):
    id: Mapped[int] = Column(Integer, primary_key=True, nullable=False)


db = SQLAlchemy(model_class=Base)

class User(db.Model):
    __tablename__ = 'user'

    login: str = Column(String(250), nullable=False)
    username: str = Column(String(250), nullable=False, unique=True)
    password: str = Column(String(250), nullable=False)

    posts: Mapped[list["Post"]] = relationship()

    @hybrid_method
    def checkpassword(self, another_password):
        return checkpw(another_password.encode(), self.password.encode())
    
    @checkpassword.expression
    def checkpassword(user, another_password):
        return checkpw(user, another_password)


class Post(db.Model):
    __tablename__ = 'post'

    title: str = Column(String(250), nullable=False)
    description: str = Column(TEXT, nullable=False)
    created_at: datetime = Column(DATETIME, nullable=False)
    private: bool = Column(BOOLEAN, nullable=False)

    author_id: Mapped[int] = mapped_column(ForeignKey("user.id"))
    author: Mapped[User] = relationship(back_populates="posts")
    